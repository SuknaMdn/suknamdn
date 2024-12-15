<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Auth;
use App\Settings\MailSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en']);
        });

        // # \Opcodes\LogViewer
        LogViewer::auth(function ($request) {
            $role = auth()?->user()?->roles?->first()->name;
            return $role == config('filament-shield.super_admin.name');
        });

        // # Hooks
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::FOOTER,
        //     fn (): View => view('filament.components.panel-footer'),
        // );

        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css'),
        ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): View => view('filament.components.button-website'),
        );

        // Share the authenticated user with all views
        ViewFacade::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        // # Mail Settings
        // check if the mail settings are loaded
        // $mailSettings = app(MailSettings::class)->toArray();
        // app(MailSettings::class)->loadMailSettingsToConfig($mailSettings);
    }
}
