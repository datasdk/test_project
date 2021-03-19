<?php

    namespace App\Models\Col;


    class Col {


        public static $row_sorting = 1;
   

        public static function container($row_id,$arr){



            echo "<div id='".$row_id."' class='cl-container'>";


                foreach($arr as $content){

                    echo $content;

                }
      

            echo "</div>";



            $row_sorting = self::$row_sorting ++;


            self::insert_next_container($row_sorting);


        }
     


        public static function insert($row_id,$cols_array,$options = []){

            
            $width = false;
            $margin = 0;
            $only_container = false;
            $center = 0;
            
            $size = "";
            $xl = 0;
            $lg = 0;
            $md = 0;
            $sm = 0;
            $xs = 0;
            $all = 0;
            $cols = 0;


            $p  = 3;
            $pt = 0;
            $pr = 0;
            $pb = 0;
            $pl = 0;

            $m = 0;
            $mt = 0;
            $mr = 0;
            $mb = 0;
            $ml = 0;
            $flex = 0;
            $sm_hidden = 0;

    
            extract($options);

  

           // if(self::has_prop("sm_hidden",$options)){   $sm_hidden = 1; }
           // if(isset($options["flex"])){        $flex = 1; }
          



            if($cols){ $all = $cols; }


            if($all){

   
                if(!$xl){ $xl = $all; }
                if(!$lg){ $lg = $all; }
                if(!$md){ $md = $all; }
                if(!$sm){ $sm = $all; }
                if(!$xs){ $xs = $all; }
           

            }


            if(!$xl){ $xl = 2; }
            if(!$lg){ $lg = 2; }
            if(!$md){ $md = 2; }
            if(!$sm){ $sm = 1; }
            if(!$xs){ $xs = 1; }


            $row_sorting = self::$row_sorting++;
            
                   

            $xl = 12 / $xl;
            $lg = 12 / $lg;
            $md = 12 / $md;
            $sm = 12 / $sm;
            $xs = 12 / $xs;

       
            $amount = count($cols_array);           

            if($amount == 1){

                $xl = $lg = $md = $sm = $xs = 0;   

            }



            
            ob_start();

  

            echo "
            <div 
            class='
            ".$row_id."
            container-fluid";

            if($sm_hidden){ echo " sm_hidden "; }
            
            echo "'>";

           

            if($width != "fw"){ 
                
                echo "<div class='container'>";

            }

                
            
            echo "
            <div 
            class='row'      

            data-row_id='".$row_id."' 
            data-sorting='".$row_sorting."'
            >";
                   
            
                $i = 1;
        
                $col_sorting = 0;

                if($amount > 0)
                foreach($cols_array as $col_id => $col_content){


                    $col_sorting++;

                           
                    echo "
                        <div 
                        id='col-".$col_sorting."' 
                        class='    
                              

                        col-xl-".$xl."
                        col-lg-".$lg."
                        col-md-".$md."
                        col-sm-".$sm."
                        col-".$xs."
                   

                        p-".$p."
                        pt-".$pt."
                        pr-".$pr."
                        pb-".$pb."
                        pl-".$pl."
                  

                        m-".$m."
                        mt-".$mt."
                        mr-".$mr."
                        mb-".$mb."
                        ml-".$ml."

                        ".$size."

                        

                        ";


                        if($flex){ echo " flex "; }


                        echo "                      
                        position-relative
                        ' 

                        data-col_id='".$col_id."' data-col_sorting='".$col_sorting."'>";

                                
                               // echo "<span class='remove_col remove'><i class='far fa-times-circle'></i></span>";

                            
                            if(!empty($col_content)){
                                

                                if(!is_array($col_content)){ $col_content = [$col_content]; }
                                
                                
                                foreach($col_content as $plugin_id => $output){


                                    if(!is_int($output)){

                                        echo $output;

                                    } else {

                                        $object_ref_id = $output;

                                        echo Frontend::insert($plugin_id,$object_ref_id); 

                                    }

                                        
                                }


                            }
                                
       
                        echo "</div>";

                        $i++;

                    }


              
                echo "</div>";

     

                if($width != "fw"){ 

                    echo "</div>";

                }


            echo "</div>";

     

                self::insert_next_container($row_sorting);
                

                $content = ob_get_contents();


            ob_end_clean();


            
            return $content;


        }

        public static function has_prop($prop,$options){


            if(array_key_exists($prop,$options) or in_array($prop,$options)){   

                return true;

            }

            return false;


        }


        public static function insert_object($plugin_id,$object_ref_id){
            
            

            $object_ref_id = intval($object_ref_id);


            if(empty($object_ref_id)){ 
                
                return false; 
            
            }
     
          

            $sql = "
            select id,type 
            from Components
            where id = '".$object_ref_id."' ";


			$r = Format::current( DB::select($sql) );


            if(empty($r)){ return false; }


			$type = $r["type"];


            echo "
            <div class= 'cl-obj' 
            data-object_ref_id = '".$object_ref_id."' 
            data-plugin_id = '".$plugin_id."'>";



                if($type == "text"){

                    echo Text::insert(["object_ref_id"=>$object_ref_id]);

                }

                if($type == "article_group"){

                    echo Article::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "slider"){

                    echo Slider::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "formular"){

                    echo Formular::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "contact"){

                    echo Contact::get(["object_ref_id"=>$object_ref_id]);

                }

                
                if($type == "image"){

                    echo Image::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "information"){

                    echo Information::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "google_maps"){

                    echo Google_maps::insert(["object_ref_id"=>$object_ref_id]);

                }


                if($type == "navigationsbar"){
                    
                    echo Navigationbar::insert(["object_ref_id"=>$object_ref_id]);

                }



            echo "</div>";

            


        }
    

        public static function insert_next_container($d){



        }
        

     



    }



?>