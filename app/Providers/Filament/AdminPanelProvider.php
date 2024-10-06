<?php

namespace App\Providers\Filament;

use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\Recently\RecentlyPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use EightyNine\Approvals\ApprovalPlugin;
use EightyNine\Reports\ReportsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\ResourceLock\ResourceLockPlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Okeonline\FilamentArchivable\FilamentArchivablePlugin;
use SolutionForest\FilamentAccessManagement\FilamentAccessManagementPanel;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;
use Stephenjude\FilamentDebugger\DebuggerPlugin;
use Stephenjude\FilamentFeatureFlag\FeatureFlagPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])

            ->plugins([
                ReportsPlugin::make(),
                ResourceLockPlugin::make(),
                LightSwitchPlugin::make()
                    ->position(Alignment::BottomCenter),
                ApprovalPlugin::make(),
                \SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin::make(),
                FilamentArchivablePlugin::make(),
                FilamentApexChartsPlugin::make(),
                RecentlyPlugin::make(),
                FeatureFlagPlugin::make(),
                \Mvenghaus\FilamentScheduleMonitor\FilamentPlugin::make(),
                TableLayoutTogglePlugin::make()
                    ->persistLayoutInLocalStorage(true) // allow user to keep his layout preference in his local storage
                    ->shareLayoutBetweenPages(false) // allow all tables to share the layout option (requires persistLayoutInLocalStorage to be true)
                    ->displayToggleAction() // used to display the toggle action button automatically
                    ->toggleActionHook('tables::toolbar.search.after') // chose the Filament view hook to render the button on
                    ->listLayoutButtonIcon('heroicon-o-list-bullet')
                    ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
                DebuggerPlugin::make()
                    ->authorize(condition: fn() => auth()->user()->can('view.debuggers'))
                    ->horizonNavigation(
                        condition: fn () => auth()->user()->can('view.horizon'),
                        label: 'Horizon',
                        url: url('horizon'),
                        openInNewTab: fn () => true
                    )
                    ->telescopeNavigation(
                        condition: fn()=> auth()->user()->can('view.telescope'),
                        label: 'Telescope',
                        icon: 'heroicon-o-sparkles',
                        url: url('admin/hq_status'),
                        openInNewTab: fn () => true
                    )
                    ->pulseNavigation(
                        condition: fn () => auth()->user()->can('view.pulse'),
                        label: 'Pulse',
                        icon: 'heroicon-o-bolt',
                        url: url('admin/hq_pulse'),
                        openInNewTab: fn () => true
                    ),
                GlobalSearchModalPlugin::make(),
                FilamentAccessManagementPanel::make()
            ])
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
