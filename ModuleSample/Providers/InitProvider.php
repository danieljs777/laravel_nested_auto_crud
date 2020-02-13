<?php

namespace LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class InitProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Http';


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApi();
        $this->mapWeb();

    }

    public function mapApi()
    {
        Route::prefix('api/dbm-crm')
                ->middleware('api')
                ->name('dbm-crm.')
                ->namespace($this->namespace . '\\Controllers')
                ->group(base_path('app/Modulos/DbmCrm/Submodulos/Leads/Routes/api.php'));

    }

    public function mapWeb()
    {
        Route::prefix('dbm-crm/leads')
                ->middleware('web')
                ->name('dbm-crm.')
                ->namespace($this->namespace . '\\Controllers')
                ->group(base_path('app/Modulos/DbmCrm/Submodulos/Leads/Routes/web.php'));

    }

}
