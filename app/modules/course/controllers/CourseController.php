<?php

/*
 * Controller   : Login Controller
 * Descripttion : Handle login and Register functionallity.
 */

namespace App\Modules\Course\Controllers;

use App\Modules\Course\Models\Course;
use App\Modules\Duration\Models\Duration;
use App,
    View,
    Helpers,
    Session,
    Config,
    Redirect,
    Input,
    DB,
    Request,
    Validator,
    Auth,   
    Hash,
    Response;

class CourseController extends \BaseController {

    public $restful = true;
    
    public function __construct() {

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * curl --user admin:admin localhost/project/api/v1/pages
     */

    public function index() {

        $courses = Course::all();

//        $items = array();
//        foreach($courses as $course){
//
//            $items[] = array(
//                'course_duration_id' => $duration->course_duration_id,
//                'from' => $duration->from,
//                'to' => $duration->to,
//            );
//
//
//        }

        $response = array( 'status'=> 'success', 'message'=> 'Successfully executed','data_count' => $courses->count() );

        return $response + array( 'results' => $courses )  ;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * curl --user admin:admin -d 'title=sample&slug=abc' localhost/project/api/v1/pages
     */

    public function store() {

        // validate
        $rules = array(
            'level'      => 'required',
            'date'       => 'required',
            'time'       => 'required',
            'location'   => 'required',
            'name'       => 'required',
            //'last_name'  => 'required',
            'email'      => 'required|email',
            'phone'      => 'required',
            'comments'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            $response = array( 'status'=> 'fail', 'message'=> $validator->messages()->first() );
        } else {


            $duration = Duration::where('level','=',Input::get('level'))
                ->where('date','=',Input::get('date'))
                ->where('time','=',Input::get('time'))
                ->where('location','=',Input::get('location'))->first();

            // store
            $duration_course = new Course();
            //echo '<pre>';print_r(1);die('======Debugging=======');
            $duration_course->course_id = !empty($duration->course_id) ? $duration->course_id : '' ;
            $duration_course->name = Input::get('name');
            $duration_course->email = Input::get('email');
            $duration_course->phone = Input::get('phone');
            $duration_course->comments       = Input::get('comments');
            $duration_course->save();



            //Send Mail to Admin
            \Mail::send('emails.admin', array(
                                            'level'=> Input::get('level'),
                                            'date'=> Input::get('date'),
                                            'time'=> Input::get('time'),
                                            'location'=> Input::get('location'),
                                            'name'=> Input::get('name'),
                                            'email'=> Input::get('email'),
                                            'phone'=> Input::get('phone'),
                                            'comments'=> Input::get('comments')
                                        ),
                                    function($message){
                                        $message->to('abdulmec1976@gmail.com', Input::get('Setcapp Application'))->subject(sprintf('Registered by %s on %s %s',Input::get('name'),Input::get('date'),Input::get('time')));
            });

            //Send welcome mail to user
            \Mail::send('emails.welcome', array('firstname'=> Input::get('name')), function($message){
                $message->to(Input::get('email'), Input::get('name'))->subject('Welcome to the Setcapp App!');
            });

            if( !empty($duration_course->registration_id) ){
                $response = array( 'status'=> 'success', 'message'=> 'Successfully added' );
            }else{
                $response = array( 'status'=> 'fail', 'message'=> 'Something went wrong.' );
            }


        }

        return $response ;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     * curl --user admin:admin localhost/project/api/v1/pages/2
     */

    public function show($id) {

        $page = Page::where('id', $id)
            ->take(1)
            ->get();

        return Response::json(array(
            'status' => 'success',
            'pages' => $page->toArray()),
            200
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     * curl -i -X PUT --user admin:admin -d 'title=Updated Title' localhost/project/api/v1/pages/2
     */

    public function update($id) {

        $input = Input::all();

        $page = Page::find($id);

        if ( $input['title'] ) {
            $page->title =$input['title'];
        }
        if ( $input['slug'] ) {
            $page->slug =$input['slug'];
        }

        $page->save();

        return Response::json(array(
            'error' => false,
            'message' => 'Page Updated'),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     * curl -i -X DELETE --user admin:admin localhost/project/api/v1/pages/1
     */

    public function destroy($id) {
        $page = Page::find($id);

        $page->delete();

        return Response::json(array(
            'error' => false,
            'message' => 'Page Deleted'),
            200
        );
    }



}
