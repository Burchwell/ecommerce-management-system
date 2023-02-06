<?php

namespace App\Console;

use App\Console\Commands\Channable\Inventory\ChannableUpdateInventoryCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ChannableUpdateInventoryCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Update Cronitor if any changes are made
         */

        $schedule->command('channeladvisor:import:products')->dailyAt('00:00')
            ->thenPing('https://cronitor.link/43BC4A/complete');

        $schedule->command('channeladvisor:import:adjustments')->hourlyAt('35')
            ->thenPing('https://cronitor.link/t9v0ey/complete');

        // ChannelAdvisor
        $schedule->command('channeladvisor:import:orders')->everyFifteenMinutes()
            ->thenPing('https://cronitor.link/uNFpbd/complete');

        // Shopify
        $schedule->command('shopify:import:orders')->everyFifteenMinutes()
            ->thenPing('https://cronitor.link/ITXGZX/complete');

        // Tools
        $schedule->command('tools:update:inventory')->hourlyAt('45')
            ->thenPing('https://cronitor.link/I96pVl/complete');

        // Amazon MWS
        $schedule->command('amazon:mws:import:uid')->hourlyAt('50')
            ->thenPing('https://cronitor.link/8OvGlu/complete');

        $schedule->command('amazon:mws:import:rid')->hourlyAt('55')
            ->thenPing('https://cronitor.link/PQyIrf/complete');

        $schedule->command('amazon:mws:import:report:restock')->everyThirtyMinutes()
            ->thenPing('https://cronitor.link/yWQI8N/complete');

        $schedule->command('amazon:fba_shipments')->everyThirtyMinutes()->thenPing('https://cronitor.link/ZfBIzz/complete');

        $schedule->command('shopify:import:reviews')->dailyAt('01:00')
            ->thenPing('https://cronitor.link/GQFI3m/complete');

        $schedule->command('nova:update')->everyThirtyMinutes();

        $schedule->command('orders:tracking:update')->hourly();

        $schedule->command('orders:tracking:update')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
