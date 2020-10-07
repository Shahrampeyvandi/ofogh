<?php

namespace App\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',function($view) {
          if(auth()->check()){
            if(auth()->user()->roles->first()->broker == 1){
                $view->with('broker_name',auth()->user()->roles->first()->name);
             }else{
                 $view->with('broker_name',Role::where('id',auth()->user()->roles->first()->sub_broker)->first()->name);
           
             }
          }
            
           
        });
    }
}
