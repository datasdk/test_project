<?php

    class Video{


        public static function get($name){


            if(empty($name)){ return false; }


            $html = "";


            $object_ref_id = Frontend::set($name,"videos");


            $sql = "select * from videos where object_ref_id = '".$object_ref_id."'";

            $result = DB::select($sql);



            if(!$result){ 
                

                return false;
            

            } else {

                
                ob_start();


                    $arr = current($result);

                    $media = strtolower($arr["media"]);

                    $watch_id = $arr["watch_id"];


                    echo '
                    <iframe width="100%" height="315" 
                    src="https://www.youtube.com/embed/'.$watch_id.'" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                                    
        /*
                    echo "
                    <div 
                    class='plyr_player video' 
                    data-plyr-provider='".$media."' 
                    data-plyr-embed-id='".$watch_id."'></div>
                    ";
            */
                 

                $html = ob_get_contents();


                ob_end_clean();
        
        
                return $html; 

                
            }
        


        }


  
    }

?>