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
	protected $table = "course";
	protected $guarded = ["course_id"];
    public $primaryKey = 'course_id';
	//protected $softDelete = true;




}