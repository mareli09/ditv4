<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'archived_at',
        'last_name',
        'first_name',
        'middle_name',
        'age',
        'gender',
        'address',
        'barangay',
        'phone',
        'previously_joined'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'archived_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', ['token' => $token, 'email' => $this->email]);

        Mail::to($this->email)->send(
            new ResetPasswordMail($url, $this->first_name ?? $this->name)
        );
    }

    public function joinedActivities()
    {
        return $this->belongsToMany(Activity::class, 'activity_participants')
                    ->withPivot('participant_type')
                    ->withTimestamps();
    }
}
