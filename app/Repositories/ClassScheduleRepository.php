<?php

namespace App\Repositories;

use App\Models\ClassSchedule;

class ClassScheduleRepository
{
    public function create(array $data)
    {
        return ClassSchedule::create($data);
    }

    public function update(ClassSchedule $schedule, array $data)
    {
        $schedule->update($data);
        return $schedule;
    }

    public function delete(ClassSchedule $schedule)
    {
        return $schedule->delete();
    }
} 