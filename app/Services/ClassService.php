<?php

namespace App\Services;

use App\Repositories\ClassRepository;
use App\Repositories\ClassScheduleRepository;
use App\Repositories\ClassPricingRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ClassService
{
    protected $classRepository;
    protected $scheduleRepository;
    protected $pricingRepository;

    public function __construct(
        ClassRepository $classRepository,
        ClassScheduleRepository $scheduleRepository,
        ClassPricingRepository $pricingRepository
    ) {
        $this->classRepository = $classRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->pricingRepository = $pricingRepository;
    }

    public function getClasses(int $siteSettingId)
    {
        return $this->classRepository->getClasses($siteSettingId);
    }

    public function getClassesWithSchedules(int $siteSettingId)
    {
        return $this->classRepository->getClassesWithSchedules($siteSettingId);
    }

    public function getTimetableData(int $siteSettingId)
    {
        $classes = $this->classRepository->getClassesWithSchedules($siteSettingId);
        
        $timetableData = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        $timeSlots = [];
        foreach ($classes as $class) {
            foreach ($class->schedules as $schedule) {
                $timeSlot = $this->createCustomTimeSlotFromTime($schedule->start_time, $schedule->end_time);
                $timeSlots[$timeSlot] = $timeSlot;
            }
        }
        
        foreach ($timeSlots as $timeSlot) {
            $timetableData[$timeSlot] = [];
            foreach ($days as $day) {
                $timetableData[$timeSlot][$day] = null;
            }
        }
        
        foreach ($classes as $class) {
            foreach ($class->schedules as $schedule) {
                $timeSlot = $this->createCustomTimeSlotFromTime($schedule->start_time, $schedule->end_time);
                
                $timetableData[$timeSlot][$schedule->day] = $class;
            }
        }
        
        return $timetableData;
    }

    public function getClassTypes(int $siteSettingId)
    {
        return $this->classRepository->getClassTypes($siteSettingId);
    }

    private function createCustomTimeSlotFromTime($startTime, $endTime)
    {
        $startFormatted = date('g:ia', strtotime($startTime));
        $endFormatted = date('g:ia', strtotime($endTime));
        return "{$startFormatted} - {$endFormatted}";
    }

    public function createClass(array $data , int $siteSettingId)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['site_setting_id'] = $siteSettingId;
        $image = $data['image'] ?? null;
        $branchIds = $data['branch_ids'] ?? [];
        unset($data['image'], $data['branch_ids']);

        $class = $this->classRepository->create($data);

        $class->trainers()->sync($data['trainers'] ?? []);

        // Assign branches
        if (!empty($branchIds)) {
            $class->branches()->sync($branchIds);
        }

        foreach ($data['schedules'] ?? [] as $schedule) {
            $this->scheduleRepository->create(array_merge($schedule, ['class_id' => $class->id]));
        }

        foreach ($data['pricings'] ?? [] as $pricing) {
            $this->pricingRepository->create(array_merge($pricing, ['class_id' => $class->id]));
        }

        if ($image) {
            $class->addMedia($image)->toMediaCollection('class_images');
        }

        return $class->load(['trainers', 'schedules', 'pricings', 'branches']);
    }

    public function updateClass($class, array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        $image = $data['image'] ?? null;
        $branchIds = $data['branch_ids'] ?? [];
        unset($data['image'], $data['branch_ids']);

        $this->classRepository->update($class, $data);

        $class->trainers()->sync($data['trainers'] ?? []);

        // Update branch assignments
        if (!empty($branchIds)) {
                $class->branches()->sync($branchIds);
        }
        
        $class->schedules()->delete();
        
        foreach ($data['schedules'] ?? [] as $schedule) {
            $this->scheduleRepository->create(array_merge($schedule, ['class_id' => $class->id]));
        }

        $class->pricings()->delete();

        foreach ($data['pricings'] ?? [] as $pricing) {
            $this->pricingRepository->create(array_merge($pricing, ['class_id' => $class->id]));
        }

        if ($image) {
            $class->clearMediaCollection('class_images');
            $class->addMedia($image)->toMediaCollection('class_images');
        }

        return $class->load(['trainers', 'schedules', 'pricings', 'branches']);
    }

    public function showClass($class)
    {
        return $this->classRepository->findById($class->id, ['schedules', 'pricings', 'trainers', 'media','branches']);
    }

    public function deleteClass($class)
    {
        return $this->classRepository->delete($class);
    }

    public function getClassesWithPagination(int $siteSettingId, $perPage = 15, $search = null, $type = null)
    {
        return $this->classRepository->getAll(where: ['site_setting_id' => $siteSettingId], with: ['trainers', 'schedules', 'pricings'], perPage: $perPage, search: $search, type: $type);
    }
} 