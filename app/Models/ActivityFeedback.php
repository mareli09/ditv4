<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityFeedback extends Model
{
    use HasFactory;

    protected $table = 'activity_feedback';

    protected $fillable = [
        'activity_id','user_id','role','source','rating','comment'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
