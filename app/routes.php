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

Route::get('/', function()
{
	return View::make('hello');
});


Route::api ( ['version' => 'v1' , 'prefix' => 'api' , 'protected' => false ] , function() //'before' => 'checktoken' ,
{

	/*Route::get ( 'scrape/american' , function() {

        $response = array( );


        $query = ' SELECT items.*, category.name FROM items,category ' .
            ' WHERE items.category_id = category.category_id AND category.name = "american" limit 0,12 ';

        $news = DB::select ( $query ) ;
        //echo '<pre>';print_r();die('======Debugging=======');

        $cnews = array () ;

        if( count($news) > 0 ){

            foreach ( $news as $news_ ) {
                //Number of vis by users
                $viewCount = ! empty ( $news_->number_of_views ) ? $news_->number_of_views : "0" ;
                $nwsRow = [
                    'item_id'      => $news_->item_id ,
                    'phone'        => $news_->phone ,
                    'description'      => $news_->description ,
                    'image'        => Helpers::build_image ( $news_->image ) ,
                ] ;

                $cnews[] = ( object ) $nwsRow ;
            }

            $response = array( 'status'=> 'success', 'message'=> 'Successfully executed','data_count' => count($news) );

        }else{
            $response = array( 'status'=> 'fail', 'message'=> 'Sorry, There is no relevant  data.', );
        }



        return $response + array( 'results' => $cnews )  ;

    } ) ;*/


	Route::get ( 'scrape/american/{category}' , function($category) {


		$response = array( );
		$catArray = array('cadillac','dodgenchrysler','chevrolet','gmc','fordnlincoln','hummer','jeep');

		$cnews = array () ;

		if( !empty($category) && in_array($category,$catArray)){

			//Limit Query
			$limitArr = Helpers::apiLimitQuery();

			$query = ' SELECT items.*, category.name FROM items,category ' .
				' WHERE items.category_id = category.category_id AND category.name = "'.$category.'" '.$limitArr['query'].' ';

			$news = DB::select ( $query ) ;
			//echo '<pre>';print_r();die('======Debugging=======');

			if( count($news) > 0 ){

				foreach ( $news as $news_ ) {

					$nwsRow = [
						'item_id'      => $news_->item_id ,
						'phone'        => $news_->phone ,
						'phone1'        => $news_->phone1 ,
						'phone2'        => $news_->phone2 ,
						'description'      => $news_->description ,
						'image'        => Helpers::build_image ( $news_->image, $category ) ,
					] ;

					$cnews[] = ( object ) $nwsRow ;
				}

				$response = array( 'status'=> 'success', 'message'=> 'Successfully executed','data_count' => count($news) );

			}else{
				$response = array( 'status'=> 'fail', 'message'=> 'Sorry, There is no relevant data found.' );
			}

		}else{
			$response = array( 'status'=> 'fail', 'message'=> 'Sorry, Request can not be executed.' );
		}

		return $response + array( 'results' => $cnews )  ;

	} ) ;





});



