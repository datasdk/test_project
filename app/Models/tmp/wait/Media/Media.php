
<?php

    namespace App\Models\Api\Api;


    class Media{


        public static $config;
        public static $sounds;
        public static $cloud_name;
        public static $api_key;
        public static $api_secret;
        public static $preset;



        public static function load($arr = []){


            $page = 1;
            $pr_page = false;
            $order_by = "from_time desc";
            $ignore_limit = false;
            $from = false;
            $to = false;
            $limit = false;
            $desc = false;
            $customer_ref_id = 0;
          

            
            extract($arr);
            
            
            if(is_string($from)){ $from = strtotime($from); }

            if(is_string($to)){ $to = strtotime($to); }
          
         

            if($from === false){ $from = strtotime("midnight"); }
            else { $from = strtotime("midnight",$from); }

            if($to === false){ $to = strtotime("midnight + 1 day - 1 second"); }
            else { $to = strtotime("midnight + 1 day - 1 second",$to); }
       
           



            
            $offset = ($page - 1) * $pr_page;
            

            $sql = "select * from media where 1 ";

            
            $sql.= " and ( ";
    


            if(!$ignore_limit){ 


                $sql.= "

                always_avariable = 1
    
                OR
    
                (

                    always_avariable = 0 
                        
                    and 

                ";

            }


            $sql .= "
                (

                    ( from_time >= '".$from."' and from_time <= '".$to."' )
                                                    
                    OR 
                            
                    ('".$from."' >= from_time and '".$from."' <= to_time)                           
                            
                    OR
                                
                    ('".$to."' >= from_time and '".$to."' <= to_time)
            
                )";


            if(!$ignore_limit){ 

                $sql .= " ) ";

            }
              
            
            $sql .= " ) ";
                    
          


            if($order_by){ $sql .= " order by ".$order_by." "; }
            
            if($desc){ $sql .= " DESC "; }

            if($pr_page){  $sql .= " limit ".$offset.",".$pr_page." "; }
            

   

            $result = DB::select($sql);

        

            if($limit){

                array_splice($result,$limit);

            }
            

            self::$sounds = $result;

            self::connect();

        }


        public static function get( $arr =[] ){

            $id = 0;
            $page = 1;
            $pr_page = false;
            $limit = false;
            $from = false;
            $to = false;

            $ignore_limit = false;
            $order_by = "from_time";
            $desc = false;


            extract($arr);

           

            if(empty(self::$config)){

                self::connect();                

            }

/*
            if($from)
            if(is_string($from)){ $from = strtotime($from); }

            if($to)
            if(is_string($to)){ $to = strtotime($to); }
*/

    
            $params = ["page"=>$page,
                       "pr_page"=>$pr_page,
                       "order_by"=>$order_by,
                       "desc"=>$desc,
                       "ignore_limit"=>$ignore_limit,
                       "from"=>$from,
                       "to"=>$to,
                       "limit"=>$limit
                       ];
            // sa($params);          
            self::load($params);


            $sound = self::$sounds;
  

            
            if(empty($id)){

                return $sound;

            } 
            
            else 
            
            if(isset($sound[$id])){

                return [$sound[$id]];

            }


            return false;

        }



        public static function connect(){

            
            if(!empty(self::$config)){ return false; }


            $sql = "select * from admin_cloudinary limit 1";

            $result = current(DB::select($sql));

            self::$cloud_name   = $result["cloud_name"];
            self::$api_key      = $result["api_key"];
            self::$api_secret   = $result["api_secret"];
            self::$preset       = $result["preset"];


            self::$config = 
            \Cloudinary::config(array( 
                "cloud_name" => self::$cloud_name,
                "api_key"    => self::$api_key, 
                "api_secret" => self::$api_secret
            ));


         }




        public static function upload($path,$name = false){

            
            if(empty(self::$cloud_name)){

                self::load();

            }

            
            if(!$name){ $name = uniqid(); }
            else{ $name = sha1($_SERVER['HTTP_HOST']."_".$name); }
                
                
            $options = array(
               "public_id" => $name,
               "resource_type" => "video"
            );
            
        
           

            $info = \Cloudinary\Uploader::upload($path, $options );

     
            return $info;


        } 



       


        public static function pager( $total_items , $item_pr_page = 50, $active_page = 1, $limit = 5){

            
            if($limit)
            if($item_pr_page <= $limit){

               // return false;

            }

            // do not count the first
          
            
            $total_tabs = ceil( $total_items / $item_pr_page );

            if($limit == false){ $limit = $total_tabs; }


            $prev = $active_page - 1;
            $next = $active_page + 1;

            if($prev < 1){ $prev = 1; }
            if($next > $total_tabs){ $next = $total_tabs; }


            // less than 1
            if($limit <= 1){ return false; }

           // $start_tab = floor($active_page / $limit) + 1;


            $start_tab = ($active_page) ;

            $end_tab = ($active_page + $limit) ;
            
            //if($start_tab > $total_tabs - $limit){ $start_tab = $total_tabs - $limit; }
            //if($start_tab < $limit){ $start_tab = 1; }

       


            echo '<div class="pager">';
            

                echo "<a href='javascript:show_podcast_page(1)'> <i class='fas fa-angle-double-left'></i> </a>";


                    echo "<a href='javascript:show_podcast_page(".$prev.")'> <i class='fas fa-angle-left'></i> </a>";


                        for($i = $start_tab ; $i <= $total_tabs; $i++){


                            if($i > $end_tab){ continue; }


                            $class = "";
                    
                            if($active_page == $i){

                                $class = "active";

                            }

                            echo "<a href='javascript:show_podcast_page(".$i.")' class='".$class."'>".$i."</a>";

                        }
                        

                    echo "<a href='javascript:show_podcast_page(".$next.")' > <i class='fas fa-angle-right'></i> </a>";

         
                    echo "<a href='javascript:show_podcast_page(".$total_tabs.")' class='last'> <i class='fas fa-angle-double-right'></i> </a>";
         

                echo "</div>";
            

        }




        public static function sound( $arr = [] ){

 
            
            $customer_key = 0;

            $baseurl = "";

            $today = strtotime("midnight");

            $has_share = true;

            $has_rating = true;

            

            extract($arr);
         
       
      
            $result = self::get( $arr );


            if(empty($result)){ return false; }
            

            $customer_ref_id = Login::check();
       


            $i = 0;
            
        

            foreach($result as $val){
                    
                        
                $i++;

                $media_ref_id       = $val["id"];
                $category_ref_id    = $val["category_ref_id"];
                $url                = $val["url"];
                $name               = $val["name"];
                $description        = $val["description"];
                $always_avariable   = $val["always_avariable"];

                $from_time          = $val["from_time"];
                $to_time            = $val["to_time"];
                $language_ref_id    = $val["language_ref_id"];
                $active             = $val["active"];


                
                include(__DIR__."/includes/player.php");


                       
            }

           
        }


        public static function rating($media_ref_id, $customer_ref_id = 0){

            //
            // $customer_ref_id = get rating from this customer

        
            include(__DIR__."/includes/rating.php");


        }


        
    }

           

?>

                
             
            

