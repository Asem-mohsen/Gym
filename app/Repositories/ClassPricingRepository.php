<?php

namespace App\Repositories;

use App\Models\ClassPricing;

class ClassPricingRepository
{
    public function create(array $data)
    {
        return ClassPricing::create($data);
    }

    public function update(ClassPricing $pricing, array $data)
    {
        $pricing->update($data);
        return $pricing;
    }

    public function delete(ClassPricing $pricing)
    {
        return $pricing->delete();
    }
} 