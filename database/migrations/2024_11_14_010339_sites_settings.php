<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.site_name', 'sukna');
        $this->migrator->add('sites.site_description', 'sukna');
        $this->migrator->add('sites.site_keywords', 'sukna');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'sukna');
        $this->migrator->add('sites.site_address', 'Riyadh, Saudi Arabia');
        $this->migrator->add('sites.site_email', 'info@sukna.sa');
        $this->migrator->add('sites.site_phone', '+966555555555');
        $this->migrator->add('sites.site_phone_code', '+966');
        $this->migrator->add('sites.site_location', 'Saudi Arabia');
        $this->migrator->add('sites.site_currency', 'SAR');
        $this->migrator->add('sites.site_language', 'Arabic');
        $this->migrator->add('sites.site_social', []);
    }
};
