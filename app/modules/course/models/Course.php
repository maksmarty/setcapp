<?php 
/*
 * Model        : Category
 * Descripttion : Category model for handle login,registration,logout and user's setting.
 */

namespace App\Modules\Course\Models;

use App,
    View,
    Helpers;
use Input,
    Session,
    Config,
    Auth,
    DB;


class Course extends \Eloquent {

  
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = "registration";
	protected $guarded = ["registration_id"];
    public $primaryKey = 'registration_id';
	//protected $softDelete = true;




}