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

    public static function group(): string
    {
        return 'general';
    }
}
