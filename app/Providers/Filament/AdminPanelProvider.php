<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Pulse;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\Recently\RecentlyPlugin;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use EightyNine\Reports\ReportsPlugin;
use Exception;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\ResourceLock\ResourceLockPlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Log;
use Mvenghaus\FilamentScheduleMonitor\FilamentPlugin;
use Stephenjude\FilamentDebugger\DebuggerPlugin;
use Stephenjude\FilamentFeatureFlag\FeatureFlagPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Build the plugins array conditionally
        $plugins = [
            ReportsPlugin::make(),
            ResourceLockPlugin::make(),
            LightSwitchPlugin::make()
                ->position(Alignment::BottomCenter),
            FilamentApexChartsPlugin::make(),
            FeatureFlagPlugin::make(),
            FilamentPlugin::make(),
            DebuggerPlugin::make()
                ->authorize(condition: fn() => auth()->check() && auth()->user()->can('view.debuggers'))
                ->horizonNavigation(
                    condition: fn() => false,
                )
                ->telescopeNavigation(
                    condition: fn() => auth()->check() && auth()->user()->can('view.telescope'),
                    label: 'Telescope',
                    icon: 'heroicon-o-sparkles',
                    url: url('admin/hq_status'),
                    openInNewTab: fn() => true
                )
                ->pulseNavigation(
                    condition: fn() => auth()->check() && auth()->user()->can('view.pulse'),
                    label: 'Pulse',
                    icon: 'heroicon-o-bolt',
                    url: url('admin/hq_pulse'),
                    openInNewTab: fn() => true
                ),
            FilamentSocialitePlugin::make()
                ->providers([
                    Provider::make('google')
                        ->label('Google')
                        ->icon('heroicon-o-globe-alt')
                        ->color(Color::Red)
                        ->outlined(false)
                        ->scopes(['openid', 'profile', 'email'])
                        ->with(['hd' => 'wolberg.pro']) // Optional: restrict to specific domain
                ])
                ->slug('auth')
                ->rememberLogin(true)
        ];

        // Only add RecentlyPlugin if the table exists
        try {
            if (Schema::hasTable('recent_entries')) {
                $plugins[] = RecentlyPlugin::make()
                    ->maxItems(20);
            }
        } catch (Exception $e) {
            // If database connection fails, skip the plugin
            Log::warning('Could not check for recent_entries table: ' . $e->getMessage());
        }

        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->login()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pulse::class
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->plugins($plugins)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
