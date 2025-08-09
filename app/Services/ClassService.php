<?php

namespace App\Services;

use App\Repositories\ClassRepository;
use App\Repositories\ClassScheduleRepository;
use App\Repositories\ClassPricingRepository;
use Illuminate\Support\Str;

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

    public function createClass(array $data , int $siteSettingId)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['site_setting_id'] = $siteSettingId;
        $image = $data['image'] ?? null;
        unset($data['image']);

        $class = $this->classRepository->create($data);

        $class->trainers()->sync($data['trainers'] ?? []);

        foreach ($data['schedules'] ?? [] as $schedule) {
            $this->scheduleRepository->create(array_merge($schedule, ['class_id' => $class->id]));
        }

        foreach ($data['pricings'] ?? [] as $pricing) {
            $this->pricingRepository->create(array_merge($pricing, ['class_id' => $class->id]));
        }

        if ($image) {
            $class->addMedia($image)->toMediaCollection('class_images');
        }

        return $class->load(['trainers', 'schedules', 'pricings']);
    }

    public function updateClass($class, array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        $image = $data['image'] ?? null;
        unset($data['image']);

        $this->classRepository->update($class, $data);

        $class->trainers()->sync($data['trainers'] ?? []);
        
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

        return $class->load(['trainers', 'schedules', 'pricings']);
    }

    public function showClass($class)
    {
        return $this->classRepository->findById($class->id, ['schedules', 'pricings', 'trainers']);
    }

    public function deleteClass($class)
    {
        // Cascade deletes handled by DB
        return $this->classRepository->delete($class);
    }
} 