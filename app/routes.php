<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::get('/', function()
//{
//	//use Jenssegers\Agent\Agent;
////Jenssegers\Agent\Facades\Agent::
//	$agent = new Jenssegers\Agent\Agent();
//	echo $agent->is('OS X');
//
//    Mail::send('emails.welcome', array('firstname'=> 'Akbar'), function($message){
//        $message->to('makhan.amu1@gmail.com', 'Akbar Khan ')->subject('Welcome to the Setcapp App!');
//    });
//
//	return View::make('hello');
//});


Route::api ( ['version' => 'v1' , 'prefix' => 'api' , 'protected' => false ] , function() //'before' => 'checktoken' ,
{

	route::get ( 'weshinearbic' , function() {

		//$response = array( );
		$agent = new Jenssegers\Agent\Agent();
        //echo '<pre>';print_r($agent->getRules());die('======Debugging======='); die;

		if( $agent->is('iPhone') && $agent->is('iPad') ){

			$url = 'https://itunes.apple.com/kw/app/ipad-for-alshms-almrht/id969837444?mt=8';
			return Redirect::away($url);

		}elseif( $agent->is('iPhone') && $agent->isMobile() ){

			$url = 'https://appsto.re/kw/mUOZ5.i';
			return Redirect::away($url);

		}elseif( $agent->isAndroidOS() && $agent->isMobile() ){

            $url = 'https://play.google.com/store/apps/details?id=com.moderneng.arbeng&hl=en';
			return Redirect::away($url);

		}else{
			return array( 'status'=> 'fail', 'message'=> 'Unauthorize access.' );
		}

	} ) ;

    route::get ( 'course' , function() {

        $response = array();

        $query = ' SELECT * FROM course WHERE status = "1" GROUP BY level';

        //echo $query;
        $news = \DB::select ( $query ) ;

        //echo '<pre>';print_r($news);die('======Debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {

                $nwsrow = [
                    'level'       => $news_->level ,
                    //'date'        => $news_->date ,
                    //'time'        => $news_->time ,
                    //'location'    => $news_->location

                ] ;

                $cnews[] = ( object ) $nwsrow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;

    //http://www.setcapp.com/api/course/<level>/<date>/<time>/<location>
    route::get ( 'course/{level}' , function($level) {

        $response = array();

        $query = ' SELECT * FROM course WHERE status = "1" AND level LIKE "'.$level.'%"  GROUP BY `date` ';


        //echo $query;
        $news = \DB::select ( $query ) ;

        //echo '<pre>';print_r($news);die('======Debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {

                $nwsrow = [
                    //'level'       => $news_->level ,
                    'date'        => $news_->date ,
                    //'time'        => $news_->time ,
                    //'location'    => $news_->location

                ] ;

                $cnews[] = ( object ) $nwsrow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;



    //http://www.setcapp.com/api/course/<level>/<date>/<time>/<location>
    route::get ( 'course/{level}/{date}' , function($level , $date ) {

        $response = array();

        $query = ' SELECT * FROM course WHERE status = "1" ';


        if( !empty($date) ){
            $query .= ' AND level LIKE "'.$level.'%"  ';
        }

        if( !empty($date) ){
            $query .= ' AND date LIKE "'.$date.'%" ';
        }

        $query .= ' GROUP BY `time` ';


        //echo $query;
        $news = \DB::select ( $query ) ;

        //echo '<pre>';print_r($news);die('======Debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {

                $nwsrow = [
                    //'level'       => $news_->level ,
                    //'date'        => $news_->date ,
                    'time'        => $news_->time ,
                    //'location'    => $news_->location

                ] ;

                $cnews[] = ( object ) $nwsrow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;


    route::get ( 'course/{level}/{date}/{time}' , function($level, $date , $time ) {

        $response = array();

        $query = ' SELECT * FROM course WHERE status = "1" ';


        if( !empty($date) ){
            $query .= ' AND level LIKE "'.$level.'%"  ';
        }

        if( !empty($date) ){
            $query .= ' AND date LIKE "'.$date.'%" ';
        }

        if( !empty($time) ){
            $query .= ' AND time LIKE "'.$time.'%" ';
        }


        $query .= ' GROUP BY `location` ';



        //echo $query;
        $news = \DB::select ( $query ) ;

        //echo '<pre>';print_r($news);die('======Debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {

                $nwsrow = [
                    //'level'       => $news_->level ,
                    //'date'        => $news_->date ,
                    //'time'        => $news_->time ,
                    'location'    => $news_->location

                ] ;

                $cnews[] = ( object ) $nwsrow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;


//	route::get ( 'scrape/american/{category}' , function($category) {
//
//
//		$response = array( );
//		$catarray = array('cadillac','dodgenchrysler','chevrolet','gmc','fordnlincoln','hummer','jeep');
//
//		$cnews = array () ;
//
//		if( !empty($category) && in_array($category,$catarray)){
//
//			//limit query
//			$limitarr = helpers::apilimitquery();
//
//			$query = ' select items.*, category.name from items,category ' .
//				' where items.category_id = category.category_id and category.name = "'.$category.'" '.$limitarr['query'].' ';
//
//			$news = db::select ( $query ) ;
//			//echo '<pre>';print_r();die('======debugging=======');
//
//			if( count($news) > 0 ){
//
//				foreach ( $news as $news_ ) {
//
//					$nwsrow = [
//						'item_id'      => $news_->item_id ,
//						'phone'        => $news_->phone ,
//						'phone1'        => $news_->phone1 ,
//						'phone2'        => $news_->phone2 ,
//						'description'      => $news_->description ,
//						'image'        => helpers::build_image ( $news_->image, $category ) ,
//					] ;
//
//					$cnews[] = ( object ) $nwsrow ;
//				}
//
//				$response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );
//
//			}else{
//				$response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant data found.' );
//			}
//
//		}else{
//			$response = array( 'status'=> 'fail', 'message'=> 'sorry, request can not be executed.' );
//		}
//
//		return $response + array( 'results' => $cnews )  ;
//
//	} ) ;
//




});



