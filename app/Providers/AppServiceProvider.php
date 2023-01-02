<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (!defined('PURCHORDER')) define('PURCHORDER', '101');
        if (!defined('PURCHINVOICE')) define('PURCHINVOICE', '102');
        if (!defined('SALESORDER')) define('SALESORDER', '201');
        if (!defined('SALESINVOICE')) define('SALESINVOICE', '202');
        if (!defined('OTHERSALESINVOICE')) define('OTHERSALESINVOICE', '203');
        if (!defined('DELIVERYORDER')) define('DELIVERYORDER', '301');
        if (!defined('STOCKMOVEIN')) define('STOCKMOVEIN', '401');
        if (!defined('STOCKMOVEOUT')) define('STOCKMOVEOUT', '402');
        if (!defined('PURCHASEADDITIONALCOST')) define('PURCHASEADDITIONALCOST', '404');


        if(env('SUPPRESS_ERROR')){

            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

        }
    

        \Illuminate\Pagination\Paginator::useBootstrap();

        view()->composer('*', function ($view) {
            if (\Auth::check()) {
                $org_id = \Auth::user()->org_id;
                $app_theme = \Auth::user()->settings()->get('theme.'.$org_id, null);

                //...with this variable
                $view->with('app_theme', $app_theme);
            }
        });

        \Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        // Manually registering provider only if the environment is set to
//        // development. That prevents a loading failure in PROD when the
//        // package is not present.
//        if ($this->app->environment('development')) {
//            $this->app->register('Libern\SqlLogging\SqlLoggingServiceProvider');
//        }
    }
}
