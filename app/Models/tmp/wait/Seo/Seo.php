<?php


    namespace App\Models\Api\Api;


    Class Seo{


        public static function meta(){



            $url = $_SERVER["REQUEST_URI"];

            $company = Company::get();




            $title 			= strtoupper($_SERVER["HTTP_HOST"]);
            $description 	= "";
            $keywords 		= "";
            $robots 		= "noindex,nofollow";

            $og_image       = false;
            

            // PAGES

            $sql = "select * from pages where url = '".$url."' and active = 1 limit 1";

            $page_result = DB::select($sql);


            // categories
            
            if(!$page_result){

                $sql = "select * from categories_url where url = '".$url."' and active = 1 limit 1";

                $result = DB::select($sql);

            }
            
            // product

            if(!$page_result){

                $sql = "select * from products_url where url = '".$url."' and active = 1 limit 1";

                $result = DB::select($sql);

            }

            // article

            if(!$page_result){

                $sql = "select * from articles_url where url = '".$url."' and active = 1 limit 1";

                $result = DB::select($sql);

            }
            


            if($page_result){

                $page_result = current($page_result);

                $title 			= Sentence::get($page_result["seo_title"]);
                $description 	= Sentence::get($page_result["seo_description"]);
                $keywords 		= Sentence::get($page_result["seo_keywords"]);
        
              

                if($page_result["visible"]){ $robots = "index,follow"; }

            } 


  
 


            
            echo '<title>'.$title.'</title>';

            echo '<meta name="title" content="'.$title.'" />';
            echo '<meta name="description" content="'.$description.'">';
            echo '<meta name="keywords" content="'.$keywords.'">';
            echo '<meta name="robots" content="'.$robots.'">';
            
            echo '<meta name="generator" content="Datas">';
            echo '<meta name="robots" content="All" />';
            echo '<meta name="format-detection" content="telephone=yes"/>';
            echo '<meta name="revisit-after" content="7 days">';
            echo '<meta name="no-email-collection" content="http://www.metatags.info/nospamharvesting">';
            
        
            
        
            echo '<meta property="og:title" content="'.$title.'" />';
            echo '<meta property="og:description" content="'.$description.'"/>';



            // if og image dosent exists - get defautl
            if(empty($og_image))
            if(isset($company["og_image_ref_id"])){

                $og_image_ref_id = $company["og_image_ref_id"];
          
                if($og_image_ref_id){

                    $og_image = Cloudi::get($og_image_ref_id);

                }
                
                    
            }


            if($og_image){

                echo '<meta property="og:image" content="'.$og_image.'"/>';

            }
            
 
        

        }




    }

  

?>