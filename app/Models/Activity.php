<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','venue','start_date','end_date','start_time','end_time',
        'conducted_by','fee','description','attachments','created_by'
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
}
