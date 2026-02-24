<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','venue','start_date','end_date','start_time','end_time',
        'conducted_by','fee','description','attachments','created_by','archived_at',
        'entry_code','requires_entry_code'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'attachments' => 'array',
        'archived_at' => 'datetime',
    ];

    public function feedback()
    {
        return $this->hasMany(ActivityFeedback::class);
    }

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    /**
     * Generate a unique entry code for the activity
     */
    public static function generateEntryCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('entry_code', $code)->exists());

        return $code;
    }

    /**
     * Scope to get only active (not archived) activities
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }
}
