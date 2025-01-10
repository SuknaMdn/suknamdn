<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManagePages extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationGroup = 'Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // term and condition
                Forms\Components\Section::make('Term and Condition')
                ->label(fn () => __('page.general_settings.sections.term_and_condition.title'))
                ->description(fn () => __('page.general_settings.sections.term_and_condition.description'))
                ->schema([
                    RichEditor::make('term_and_condition')
                        ->label(fn () => __('page.general_settings.fields.term_and_condition'))
                        ->required(),
                ])->columns(1),

                // privacy policy
                Forms\Components\Section::make('Privacy Policy')
                ->label(fn () => __('page.general_settings.sections.privacy_policy.title'))
                ->description(fn () => __('page.general_settings.sections.privacy_policy.description'))
                ->schema([
                    RichEditor::make('privacy_policy')
                        ->label(fn () => __('page.general_settings.fields.privacy_policy'))
                        ->required(),
                ])->columns(1),

                // about
                Forms\Components\Section::make('About')
                ->label(fn () => __('page.general_settings.sections.about.title'))
                ->description(fn () => __('page.general_settings.sections.about.description'))
                ->schema([
                    RichEditor::make('about')
                        ->label(fn () => __('page.general_settings.fields.about'))
                        ->required(),
                ])->columns(1),
            ]);
    }
}
