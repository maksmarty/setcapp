<?php 
namespace App\Modules\Course;
 
class ServiceProvider extends \App\Modules\ServiceProvider {
 
    public function register()
    {
        parent::register('course');
    }
 
    public function boot()
    {
        parent::boot('course');
    }
 
}