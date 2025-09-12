<?php

namespace App\Enums;

use Carbon\Carbon;

enum MembershipPeriod: string
{
    case DAY = 'One Day';
    case MONTH = 'Month';
    case THREE_MONTHS = '3 Month';
    case SIX_MONTHS = '6 Month';
    case YEAR = 'Year';
    case TWO_YEARS = '2 Years';
    case THREE_YEARS = '3 Years';
    case FOUR_YEARS = '4 Years';
    case SIX_YEARS = '6 Years';

    /**
     * Get all available periods as array for form options
     */
    public static function getOptions(): array
    {
        return [
            ['value' => self::DAY->value, 'label' => self::DAY->value],
            ['value' => self::MONTH->value, 'label' => self::MONTH->value],
            ['value' => self::THREE_MONTHS->value, 'label' => self::THREE_MONTHS->value],
            ['value' => self::SIX_MONTHS->value, 'label' => self::SIX_MONTHS->value],
            ['value' => self::YEAR->value, 'label' => self::YEAR->value],
            ['value' => self::TWO_YEARS->value, 'label' => self::TWO_YEARS->value],
            ['value' => self::THREE_YEARS->value, 'label' => self::THREE_YEARS->value],
            ['value' => self::FOUR_YEARS->value, 'label' => self::FOUR_YEARS->value],
            ['value' => self::SIX_YEARS->value, 'label' => self::SIX_YEARS->value],
        ];
    }

    /**
     * Get the number of months for this period
     */
    public function getMonths(): int
    {
        return match($this) {
            self::DAY => 0,
            self::MONTH => 1,
            self::THREE_MONTHS => 3,
            self::SIX_MONTHS => 6,
            self::YEAR => 12,
            self::TWO_YEARS => 24,
            self::THREE_YEARS => 36,
            self::FOUR_YEARS => 48,
            self::SIX_YEARS => 72,
        };
    }

    /**
     * Calculate end date from a given start date
     */
    public function calculateEndDate(?Carbon $startDate = null): Carbon
    {
        $startDate = $startDate ?? now();
        
        if ($this === self::DAY) {
            return $startDate->copy()->addDay();
        }
        
        return $startDate->copy()->addMonths($this->getMonths());
    }

    /**
     * Get display label for the period
     */
    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Check if period is valid
     */
    public static function isValid(string $period): bool
    {
        return in_array($period, array_column(self::cases(), 'value'));
    }

    /**
     * Create enum from string value
     */
    public static function fromString(string $period): ?self
    {
        return self::tryFrom($period);
    }

    /**
     * Get the billing interval for this period
     */
    public function getBillingInterval(): string
    {
        return match($this) {
            self::DAY => 'daily',
            self::MONTH, self::THREE_MONTHS, self::SIX_MONTHS => 'monthly',
            self::YEAR, self::TWO_YEARS, self::THREE_YEARS, self::FOUR_YEARS, self::SIX_YEARS => 'yearly',
        };
    }

    /**
     * Get billing interval from period string
     */
    public static function getBillingIntervalFromPeriod(string $period): string
    {
        $periodEnum = self::fromString($period);
        return $periodEnum ? $periodEnum->getBillingInterval() : 'monthly';
    }
}
