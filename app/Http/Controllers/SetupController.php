<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    public function index()
    {
        return view('setup.index');
    }

    public function addEntryCodeColumn()
    {
        try {
            // Check if entry_code column already exists
            if (!Schema::hasColumn('activities', 'entry_code')) {
                DB::statement('ALTER TABLE activities ADD COLUMN entry_code VARCHAR(6) UNIQUE AFTER id');
                DB::statement('ALTER TABLE activities ADD COLUMN requires_entry_code BOOLEAN DEFAULT TRUE AFTER entry_code');
            }

            // Check if archived_at column already exists in activities
            if (!Schema::hasColumn('activities', 'archived_at')) {
                DB::statement('ALTER TABLE activities ADD COLUMN archived_at TIMESTAMP NULL AFTER updated_at');
            }

            // Generate entry codes for existing activities that don't have one
            $activities = DB::table('activities')->whereNull('entry_code')->get();

            foreach ($activities as $activity) {
                $code = $this->generateUniqueCode();
                DB::table('activities')
                    ->where('id', $activity->id)
                    ->update(['entry_code' => $code]);
            }

            return redirect()->back()->with('success', 'Database setup completed successfully! Entry codes have been added to activities.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique entry code
     */
    private function generateUniqueCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (DB::table('activities')->where('entry_code', $code)->exists());

        return $code;
    }
}
