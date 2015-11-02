<?php 
namespace App\Modules\Duration;
 
class ServiceProvider extends \App\Modules\ServiceProvider {
 
    public function register()
    {
        parent::register('duration');
    }
 
    public function boot()
    {
        parent::boot('duration');
    }
 
}