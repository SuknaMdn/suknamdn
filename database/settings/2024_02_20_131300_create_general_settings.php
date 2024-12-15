<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', 'sukna');
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logoHeight', '3rem');
        $this->migrator->add('general.site_active', true);
        $this->migrator->add('general.site_favicon', 'sites/logo.ico');
        $this->migrator->add('general.site_theme', [
            "primary" => "#F2C849",
            "secondary" => "#423DD9",
            "gray" => "#3b3b3b",
            "success" => "#1DCB8A",
            "danger" => "#ff5467",
            "info" => "#6E6DD7",
            "warning" => "#f5de8d",
        ]);
    }
};
