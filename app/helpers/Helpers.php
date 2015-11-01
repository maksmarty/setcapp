<?php

class Helpers {

    public static function dump($array) {

        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
    
    /*
    ********************************************************************************************************
                                           CRON 
    ********************************************************************************************************
    */
    
    # split process into multiple process
    public static function can_i_run($process) {
            $output = `ps aux | grep patcognos-"$process"`;
            $lines = explode( "\n", $output );
            foreach( $lines as $k => $line ) {
                    if( empty( $line ) ) {
                            unset( $lines[ $k ] );
                    }
            }
            Helpers::dump($lines);

            // Current process is initiated. So, one more isntance need to be checked.
            return count( $lines ) >= 53 ? false : true; //Allow 50 instances
    }

    public static function can_cron_run($process) {
        $can_run = false;
        if (PHP_OS != 'WINNT') {
            if (\Helpers::can_i_run($process) == false) {
                $can_run = false;
                echo "Can't run yet, waiting for previous instance to finish.";
            } else {
                $can_run = true;
                echo "Good to run now \n";
            }
        } else {
            $can_run = true;
            echo "Windows Good to run now \n";
        }

        if ($can_run) {
            return true;
        } else {
            echo "Can't run yet, waiting for previous instance to finish.";
        }
    }
    
    /*
    ********************************************************************************************************
                                           Pagination
    ********************************************************************************************************
    */
    public static function requestToParamsForBackEnd($numResults, $totalPages, $firstPage, $lastPage, $currentPage, $start, $sort, $order, $q, $field) {
        // function requestToParamsForBackEnd($numResults, $start, $totalPages, $firstPage, $lastPage, $currentPage, $sort, $order, $q, $field) {
        // $PER_PAGE = 2;
        $PER_PAGE = Config::get('app.per_page');
        $param = array(
            'numResults' => $numResults,
            'totalPages' => $totalPages,
            'firstPage' => $firstPage,
            'lastPage' => $lastPage,
            'currentPage' => $currentPage,
            'start' => $start,
            'end' => (($start + ($PER_PAGE - 1)) > ($numResults - 1) ? ($numResults - 1) : ($start + ($PER_PAGE - 1))),
            'perPage' => $PER_PAGE,
            'sort' => $sort,
            'order' => $order,
            'q' => $q,
            'field' => $field,
            'tab' => $field
        );
        return $param;
    }



    public static function buildPagination($param, $uri) {
        
        $html = '';
        
        
        if (!empty($param) && count($param) > 0) {
            $adjacents = 2;
            $page = $param ['currentPage'];
            // $page = $param['page'];

            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = $param ['lastPage'];
            $lpm1 = $lastpage - 1;

            if ($lastpage > 1) {
                $html .= '<ul class="pagination">';

                // previous button
                if ($page > 0) {
                    $html .= '<li class="first"><a  href="' . $uri . '/page/' . $param ['firstPage'] . '" id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $param ['firstPage'] . '">First</a></li>';
                    $html .= '<li class="prev"><a  href="' . $uri . '/page/' . $page . '" id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $prev . '"> &laquo; </a></li>';
                } else {
                    $html .= '<span class="disabledFirst"> << </span>';
                }
                // pages
                if ($lastpage < 5 + ($adjacents * 2)) {
                    for ($counter = 1; $counter <= $lastpage; $counter ++) {
                        if ($counter == $page) {
                            $html .= '<li class="current"><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                        } else {
                            $html .= '<li><a href="' . $uri . '/page/' . $counter . '" id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                        }
                    }
                } else if ($lastpage > 3 + ($adjacents * 2)) {
                    if ($page < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter ++) {
                            if ($counter == $page) {
                                $html .= '<li class="current"><a href="' . $uri . '/page/' . $counter . '" id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                            } else {
                                $html .= '<li><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                            }
                        }

                        $html .= '<li class="unavailable"><a href="">&hellip;</a></li>';

                        // $html.= '<li>...</li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $lpm1 . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $lpm1 . '">' . $lpm1 . '</a></li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $lastpage . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $lastpage . '">' . $lastpage . '</a></li>';
                    } else if ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                        $html .= '<li><a href="' . $uri . '/page/' . $param ['firstPage'] . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $param ['firstPage'] . '">1</a></li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $next . '"   id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $next . '">2</a></li>';
                        $html .= '<li>...</li>';

                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter ++) {
                            if ($counter == $page) {
                                $html .= '<li class="current"><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                            } else {
                                $html .= '<li><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                            }
                        }
                        // $html.= '<li>...</li>';
                        $html .= '<li class="unavailable"><a href="">&hellip;</a></li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $lpm1 . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $lpm1 . '">' . $lpm1 . '</a></li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $lastpage . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $lastpage . '">' . $lastpage . '</a></li>';
                   
                    } else {
                        
                        $html .= '<li><a href="' . $uri . '/page/' . $param ['firstPage'] . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $param ['firstPage'] . '">1</a></li>';
                        $html .= '<li><a href="' . $uri . '/page/' . $next . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $next . '">2</a></li>';
                        // $html.= '<li>...</li>';
                        $html .= '<li class="unavailable"><a href="">&hellip;</a></li>';

                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter ++) {
                            if ($counter == $page) {
                                $html .= '<li class="current"><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . ($counter) . '</a></li>';
                            } else {
                                $html .= '<li><a href="' . $uri . '/page/' . $counter . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $counter . '">' . $counter . '</a></li>';
                            }
                        }
                    }
                }
                // next button
                if ($page < $counter - 1) {
                    $html .= '<li class="next"><a  href="' . $uri . '/page/' . $next . '"  id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $next . '"> &raquo; </a></li>';
                } else {
                    $html .= '<li class="next disabled"><a > &raquo; </a></li>';
                }
                $html .= '<li class="last"><a   href="' . $uri . '/page/' . $param ['lastPage'] . '" id = "page' . ((isset($param ['field']) && !empty($param ['field'])) ? '__' . $param ['field'] : '') . '__' . $lastpage . '">Last</a></li>';
                $html .= '</ul>';

                return $html;
            }
        } else {
            return $html;
        }
    }

  
    
 


    public static function processItemInput($q) {

        $response = array();

        $arr_query = explode(' ', $q);

        if (!empty($q)) {

            // check if white espace is exist or not
            if(!preg_match('/\s/',($q))) {


                // check if it contain only integer
                if(preg_match("/^[0-9]+$/",$q)){

                    $response ['type'] = 'integer';
                    $response ['q'] = (int)$q;
                }

            }else if (count( $arr_query ) > 1) {

                $response ['type'] = 'string';
                $response ['q'] = $q;
            }
        } else {

            $response ['q'] = '';

        }


        return $response;
    }

    public static function buildLimitQuery($page,$limit) {

        $PER_PAGE = Config::get('app.per_page');

        $arr = array();

        $query = '';
        $start = 1;
        
        if (!empty($page) && $page == 'all') {
            // NO NEED TO LIMIT THE CONTENT
        } else {

            if (!empty($page) || $page != 0) {
                $start = ($page - 1) * $PER_PAGE;
                $query = " LIMIT $start, " . $PER_PAGE;
            } else {
                $query = " LIMIT 0, " . $PER_PAGE;
                $start = 1;
            }
        }

        $arr ['query'] = $query;
        $arr ['start'] = $start;

        return $arr;
    }

    public static function buildParamForPagination($numResults, $p, $per_page) {

        $arr = array();

        if (!empty($p) && $p == 'all') {
            $per_page = $numResults;
            $arr ['per_page'] = $per_page;
        }

        if ($numResults > 0) {
            $totalPages = ceil($numResults / $per_page);
        } else {
            $totalPages = 0;
        }

        $arr ['totalPages'] = $totalPages;
        $firstPage = 1;
        $arr ['firstPage'] = $firstPage;

        if ($totalPages > 0) {
            $lastPage = $totalPages;
        } else {
            $lastPage = 1;
        }
        $arr ['lastPage'] = $lastPage;

        $currentPage = '';
        if ($p) {
            $currentPage = $p;
        }

        if ($currentPage <= 0) {
            $currentPage = 1;
        } else if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $arr ['currentPage'] = $currentPage;

        return $arr;
    }

    public static function requestToParams($numResults, $start, $totalPages, $first, $last, $page, $sort, $order, $q, $field = null, $header = null ,  $target_tab = null,  $to_year=null ,  $from_year=null ) {
        
        global $PER_PAGE;
        $PER_PAGE = $PER_PAGE;

        $param = array(
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $PER_PAGE,
            'start' => $start,
            'end' => (($start + ($PER_PAGE - 1)) > ($numResults - 1) ? ($numResults - 1) : ($start + ($PER_PAGE - 1))),
            'firstPage' => $first,
            'lastPage' => $last,
            'numResults' => $numResults,
            'sort' => $sort,
            'order' => $order,
            'q' => $q,
            'field' => $field,
            'result_header' => $header,
            'currentPage' => $page         
        );
        
        if($to_year && $from_year){
             $param ['to_year'] = $to_year;
             $param ['from_year'] = $from_year;
        }

        $tab = $target_tab;
        
        if ($tab) {
            $param ['tab'] = $tab;
        }
        return $param;
    }
    
    /*
    ********************************************************************************************************
                                           Pagination
    ********************************************************************************************************
    */

    public static function buildErrorMessage($messages = array()) {
        $msg = null;
        $count = count($messages);
        $i  = 1;

        foreach ($messages as $message)
        {
            if( $count == 1 ){
                $msg .= $message ;
            }else{


                if(  $count == $i ){
                    $msg .= '-' . $message ;
                }else{
                    $msg .= '-' .$message . '<br>';
                    $i++;
                }


            }


        }
        return $msg;
    }



    public static function build_image($image, $categoryName, $size = '100') {

        if( !empty($image) ){
            return URL::to(sprintf('uploads/images/%s/%s/%s', $categoryName ,$size, $image));
        }else{
            return '';
        }


    }



    public static function build_delivery_image($size = '100') {
        return URL::to(sprintf('uploads/images/%s/%s/%s', 'delivery' ,$size, 'delivery.png'));
    }


    public static function build_taxi_image($size = '100') {
        return URL::to(sprintf('uploads/images/%s/%s/%s', 'taxi' ,$size, 'taxi.png'));
    }


    public static function build_movablewash_image($size = '100') {
        return URL::to(sprintf('uploads/images/%s/%s/%s', 'movablewash' ,$size, 'movablewash.png'));
    }

    public static function build_static_image($categoryName,$size = '100') {
        return URL::to(sprintf('uploads/images/%s/%s/%s', $categoryName ,$size, "{$categoryName}.png"));
    }


    public static function apiLimitQuery() {


        $page = \Input::get('page');
        if( empty($page) ){
            $page = 1;
        }

        $limit = \Input::get('limit');
        if( empty($limit) ){
            $limit = \Config::get('constant.api_limit');
        }

        $arr = array();

        $query = '';
        $start = 1;

        if (!empty($page) && $page == 'all') {
            // NO NEED TO LIMIT THE CONTENT
        } else {

            if (!empty($page) || $page != 0) {
                $start = ($page - 1) * $limit;
                $query = " LIMIT $start, " . $limit;
            } else {
                $query = " LIMIT 0, " . $limit;
                $start = 1;
            }
        }

        $arr ['query'] = $query;
        $arr ['start'] = $start;

        return $arr;
    }





}