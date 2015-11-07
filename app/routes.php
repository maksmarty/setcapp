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
//	return View::make('hello');
//});


Route::api ( ['version' => 'v1' , 'prefix' => 'api' , 'protected' => false ] , function() //'before' => 'checktoken' ,
{

	route::get ( 'weshinearbic' , function() {

		$response = array( );

		$agent = new Jenssegers\Agent\Agent();

		if( $agent->is('iPhone') && $agent->match('iPad') ){ //$agent->isTablet()

			$url = 'https://itunes.apple.com/kw/app/ipad-for-alshms-almrht/id969837444?mt=8';
			Redirect::away($url);

		}elseif( $agent->is('iPhone') && $agent->isMobile() ){

			$url = 'https://appsto.re/kw/mUOZ5.i';
			Redirect::away($url);

		}elseif( $agent->isAndroidOS() && $agent->isMobile() ){
			$url = 'https://play.google.com/store/apps/details?id=com.moderneng.arbeng&hl=en';
			Redirect::away($url);
		}else{
			return array( 'status'=> 'fail', 'message'=> 'Unauthorize access.' );
		}

	} ) ;


	/*route::get ( 'scrape/american' , function() {

        $response = array( );


        $query = ' select items.*, category.name from items,category ' .
            ' where items.category_id = category.category_id and category.name = "american" limit 0,12 ';

        $news = db::select ( $query ) ;
        //echo '<pre>';print_r();die('======debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {
                //number of vis by users
                $viewcount = ! empty ( $news_->number_of_views ) ? $news_->number_of_views : "0" ;
                $nwsrow = [
                    'item_id'      => $news_->item_id ,
                    'phone'        => $news_->phone ,
                    'description'      => $news_->description ,
                    'image'        => helpers::build_image ( $news_->image ) ,
                ] ;

                $cnews[] = ( object ) $nwsrow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'sorry, there is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;*/


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



