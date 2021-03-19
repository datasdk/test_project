<?php

    class Shortcode {

        
        public static $shortcodes = []; 



        public static function load($name,$params){

            $tags = array(
                "hr" => "<hr>"
            );

            $classes = array(
                "google_maps" => "Google_maps",
                "link" => "Link"
               // "company_info" => 2

            );
            

            if(isset($tags[$name])){

                return $tags[$name];

            }


            if(isset($classes[$name])){

                $className = $classes[$name];
                $instance = new $className();

                
                $new_params = [];

                foreach($params as $v){

                    
                    if(isset($v["name"]) and isset($v["name"])){

                        $new_params[$v["name"]] = $v["value"];

                    }
                    

                }
              

                return $instance::insert($new_params);

            }


            return false;

        }


        public static function insert($name,$params){
            
          

            $content = false;

            if(!$name){ return false; }

            $content = self::load($name,$params);

         
           // $val = self::insert($val["name"], $val["params"]);
  

            return $content;

        }



        public static function convert($orig_str){


            $str = explode("[",$orig_str);

           
         
           
            if(count($str) == 1){ return $orig_str; }


            $shortcodes = self::$shortcodes;

            
            foreach($str as $val){
                
       
                $full_shortcode = current( explode("]",$val) );
                              

                $num = count($shortcodes);

                $orig_str = str_replace("[".$full_shortcode."]","[shortcode_".$num."]",$orig_str);
                
                
                $full_shortcode = strip_tags($full_shortcode);
               

                $arr = explode(" ",$full_shortcode);

                $name = array_shift( $arr );


            
                $params = [];
                
                $quotes = 0;
                
                $i = 0;


                foreach($arr as $val2){


                    if(!isset($params[$i])){ $params[$i] = ""; }


                    $params[$i] .= $val2." ";         

                    $quotes += substr_count($val2, '"');

                    // % 2 == 0
                                        
                    if($quotes == 1){

                        // sa($val2);
                        // number is even
                                 

                    } else {


                        $p = explode("=",$params[$i]);


                        if(count($p) > 1){
                                                        
                            $params[$i] = ["name" => self::remove_quotes($p[0]), "value" => self::remove_quotes($p[1]) ];

                        }
                        

                        $quotes = 0;

                        $i++;                          


                    }
               
                    
                }


                self::$shortcodes[$num] = ["name" => $name, "params" => $params];

            
            }



            foreach(self::$shortcodes as $num => $val){

                

                $name = false;

                $params = false;

                if(isset($val["name"])){ $name = $val["name"]; }
                if(isset($val["params"])){ $params = $val["params"]; }


                if($name){

                    $content = self::insert($name, $params);

                  
                    $orig_str = str_replace("[shortcode_".$num."]",$content,$orig_str);

                }
               


            }
            


           return $orig_str;
               

        }



        public static function remove_brackets($str){

            return trim(str_replace( ["[","]"],"", $str ));

        }

        public static function remove_quotes($str){

            return trim(str_replace( ["'",'"'],"", $str ));

        }

        public static function remove_linebreaks($str){
            
            return trim(preg_replace( "/\r|\n/", "", $str ));

        }


    }

?>