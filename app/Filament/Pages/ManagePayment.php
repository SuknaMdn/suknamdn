<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManagePayment extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;
    protected static ?int $navigationSort = 99;

    protected static ?string $navigationGroup = 'Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Unit Reservation')
                ->label(fn () => __('page.general_settings.sections.unit_reservation.title'))
                ->description(fn () => __('page.general_settings.sections.unit_reservation.description'))
                ->schema([
                    Forms\Components\TextInput::make('serious_value_for_unit_reservation')
                        ->label(fn () => __('page.general_settings.fields.serious_value_for_unit_reservation'))
                        ->prefix('SAR')
                        ->required(),
                    Forms\Components\TextInput::make('payment_timeout_days')
                        ->label(fn () => __('page.general_settings.fields.payment_timeout_days'))
                        ->prefix('SAR')
                        ->required(),
                ])->columns(2),
            ]);
    }
}
