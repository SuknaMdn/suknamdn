<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $brand_name;
    public ?string $brand_logo;
    public string $brand_logoHeight;
    public bool $site_active;
    public ?string $site_favicon;
    public array $site_theme;
    public int $serious_value_for_unit_reservation;
    public int $payment_timeout_days;

    public string $term_and_condition;
    public string $privacy_policy;
    // public string $project_ownership;
    public string $about;

    public static function group(): string
    {
        return 'general';
    }
}
