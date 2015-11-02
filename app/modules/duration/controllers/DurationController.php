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
                'course_duration_id' => $duration->course_duration_id,
                'from' => $duration->from,
                'to' => $duration->to,
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
            'date_duration'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            $response = array( 'status'=> 'fail', 'message'=> $validator->messages()->first() );
        } else {

            $date_duration = Input::get('date_duration');

            $dateArr = explode('-',$date_duration);


            if( !empty($dateArr) && ( count($dateArr) === 2 ) ){

                if( !empty($dateArr[1]) ){

                    $parsed_date = str_replace('/', '-', $dateArr[1]);
                    $dateString = strtotime($parsed_date);
                    $to = date('Y-m-d',$dateString);

                    $from = date('Y-m-d',mktime(0,0,0,date('m',$dateString),$dateArr[0],date('Y',$dateString)));

                    // store
                    $duration = new Duration();
                    $duration->from       = $from;
                    $duration->to       = $to;
                    $duration->save();

                    if( !empty($duration->course_duration_id) ){
                        $response = array( 'status'=> 'success', 'message'=> 'Successfully added' );
                    }else{
                        $response = array( 'status'=> 'fail', 'message'=> 'Something went wrong.' );
                    }

                }else{
                    $response = array( 'status'=> 'fail', 'message'=> 'Please correct the format of date like 12-20/09/2015' );
                }

            }else{
                $response = array( 'status'=> 'fail', 'message'=> 'Please correct the format of date like 12-20/09/2015' );
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
