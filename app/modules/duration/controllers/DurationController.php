<?php

/*
 * Controller   : Login Controller
 * Descripttion : Handle login and Register functionallity.
 */

namespace App\Modules\Duration\Controllers;

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

class DurationController extends \BaseController {

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


        $durations = Duration::all();


        $items = array();
        foreach($durations as $duration){

            $items[] = array(
                'level' => $duration->level,
                'date' => $duration->date,
                'time' => $duration->time,
                'location' => $duration->location,
            );

        }

        $response = array( 'status'=> 'success', 'message'=> 'Successfully executed','data_count' => count($items) );
        return $response + array( 'results' => $items )  ;
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
            //'status'     => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            $response = array( 'status'=> 'fail', 'message'=> $validator->messages()->first() );
        } else {


            $dupli = Duration::where('level','=',Input::get('level'))
                ->where('date','=',Input::get('date'))
                ->where('time','=',Input::get('time'))
                ->where('location','=',Input::get('location'))->first();

            //echo '<pre>';print_r($dupli);die('======Debugging=======');

            if( !empty($dupli->course_id) ){
                $response = array( 'status'=> 'fail', 'message'=> 'Duplicate record.' );
            }else{
                // store
                $duration = new Duration();
                $duration->level        = trim(Input::get('level'));
                $duration->date         = trim(Input::get('date'));
                $duration->time         = trim(Input::get('time'));
                $duration->location     = trim(Input::get('location'));
                //$duration->status       = Input::get('status');
                $duration->save();

                if( !empty($duration->course_id) ){
                    $response = array( 'status'=> 'success', 'message'=> 'Successfully added' );
                }else{
                    $response = array( 'status'=> 'fail', 'message'=> 'Something went wrong.' );
                }
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

//    public function show($id) {
//
//        $page = Page::where('id', $id)
//            ->take(1)
//            ->get();
//
//        return Response::json(array(
//            'status' => 'success',
//            'pages' => $page->toArray()),
//            200
//        );
//    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     * curl -i -X PUT --user admin:admin -d 'title=Updated Title' localhost/project/api/v1/pages/2
     */

    public function update($id) {

        $input = Input::all();

        $page = Duration::find($id);

        if ( !empty($input['level']) ) {
            $page->level =$input['level'];
        }

        if ( !empty($input['date']) ) {
            $page->date =$input['date'];
        }

        if ( !empty($input['time']) ) {
            $page->time =$input['time'];
        }

        if ( !empty($input['location']) ) {
            $page->location =$input['location'];
        }

        if ( !empty($input['status']) ) {
            $page->status =$input['status'];
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
        $page = Duration::find($id);

        $page->delete();

        return Response::json(array(
            'error' => false,
            'message' => 'Page Deleted'),
            200
        );
    }



}
