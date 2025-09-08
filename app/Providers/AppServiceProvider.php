<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

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
        // For MySQL 8.0 and newer versions
        Schema::defaultStringLength(125);

        // Alternative: For older MySQL versions (5.7 and below)
        // Schema::defaultStringLength(191);

        // Global date formatting directive
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo ($expression) ? \Carbon\Carbon::parse($expression)->format('d/m/Y') : ''; ?>";
        });
    }
}
