<?php

namespace App\Providers;

use App\Components\KfjhTempManager;
use App\Models\TKfjhTemp;
use Illuminate\Support\Facades\Cache;
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
//        TKfjhTemp::saved(function($info){
//            $class = new KfjhTempManager();
//
//            $class_name = substr(explode('\\', get_class($class))[count(explode('\\', get_class($class))) - 1],0, -7);
//
//            $cacheKey = "$class_name:$info->id";
//
//            $cacheData = Cache::get($cacheKey);
//
//            if(!$cacheData){
//                Cache::add($cacheKey, $info,60*24*7);
//            }else{
//                Cache::put($cacheKey, $info,60*24*7);
//            }
//        });
//
//        TKfjhTemp::deleted(function($info){
//            $class = new KfjhTempManager();
//
//            $class_name = substr(explode('\\', get_class($class))[count(explode('\\', get_class($class))) - 1],0, -7);
//
//            $cacheKey = "$class_name:$info->id";
//
//            $cacheData = Cache::get($cacheKey);
//
//            if($cacheData){
//                Cache::forget($cacheKey);
//            }
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }
    }
}
