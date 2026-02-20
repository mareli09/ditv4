<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ITUserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $users = User::all();
        return view('it.users', ['users' => $users]);
    }

    public function create()
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        return view('it.users.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:IT,CESO,Faculty,Student,Community',
            'is_active' => 'boolean'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('it.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        return view('it.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:IT,CESO,Faculty,Student,Community',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('it.users.index')->with('success', 'User updated successfully!');
    }

    public function activate(User $user)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $user->update(['is_active' => 1]);
        return back()->with('success', 'User activated!');
    }

    public function deactivate(User $user)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $user->update(['is_active' => 0]);
        return back()->with('success', 'User deactivated!');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $user->delete();
        return back()->with('success', 'User archived!');
    }

    public function import(Request $request)
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) != count($header)) continue;

            $data = array_combine($header, $row);

            User::firstOrCreate(
                ['email' => $data['email'] ?? ''],
                [
                    'name' => $data['name'] ?? 'Imported User',
                    'password' => bcrypt('password'),
                    'role' => $data['role'] ?? 'Student',
                    'is_active' => $data['is_active'] ?? 1
                ]
            );
        }

        return back()->with('success', 'Users imported successfully!');
    }

    public function sampleCsv()
    {
        if (auth()->user()->role !== 'IT') {
            abort(403);
        }

        $csv = "email,name,role,is_active\n";
        $csv .= "user1@example.com,John Doe,Faculty,1\n";
        $csv .= "user2@example.com,Jane Smith,Student,1\n";
        $csv .= "user3@example.com,Bob Johnson,Community,1\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample-users.csv"'
        ]);
    }
}
