<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $table = 'class_schedules';

    protected $fillable = [
        'class_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('h:i A') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('h:i A') : null;
    }

    public function getDurationAttribute()
    {
        if ($this->attributes['start_time'] && $this->attributes['end_time']) {
            $startTime = Carbon::parse($this->attributes['start_time']);
            $endTime = Carbon::parse($this->attributes['end_time']);
            
            if ($endTime->lessThan($startTime)) {
                $endTime->addDay();
            }
            
            $minutes = $startTime->diffInMinutes($endTime);
    
            return sprintf('%02d:%02d', floor($minutes / 60), $minutes % 60);
        }
    
        return null;
    }

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
} 