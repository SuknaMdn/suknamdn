<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.serious_value_for_unit_reservation', 700);
        $this->migrator->add('general.payment_timeout_days', 7);
    }
};
