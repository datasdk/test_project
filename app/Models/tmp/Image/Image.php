
<?php


    namespace App\Models\Api\Api;

    class Image{


        
        public static function insert($arr = []){

   
            $object_ref_id = false;
            $type = "background";
            $result = "";
            $image_ref_id = 0;
            $background_size = 0;
            $stamp = 1;
            $class = [];

            extract($arr);
        

         
            if(!$object_ref_id){ return false;}


            $s = Frontend::get($object_ref_id);


            if(!$s){ return false; }


            extract($s["params"]);


            $src = Cloudi::get($image_ref_id);

            
            if(!$src){

                $src = Image::get_no_image();
        
            } else {
        
                $src = Cloudi::size($src,1280);
        
            }

      



            if($background_size == "auto"){ $type = "img"; }



         //   if($stamp){ $stamp_src = Frontend::image("stamp_".$object_ref_id,["type"=>"image"]); }



            ob_start();
            
     

                if($type == "img"){ 
                                            
                    include(__DIR__."/include/image.php");

                }
                    
                else
                    

                if($type == "background"){
                                
                    include(__DIR__."/include/background.php");
                        
                }
                

               $result = ob_get_contents();


            ob_get_clean();
            

            return $result;



        }





        public static function get($image_ref_id,$type=false,$width = 0,$height = 0,$quality=0){


            $image_ref_id = intval($image_ref_id);


            if($image_ref_id){

               
                $src = Cloudi::get($image_ref_id,$width,$height,$quality);

              
            } else {


                return false;


            }
            

            return $src;


        }     


        public static function get_no_image(){

            return "/assets/images/interface/no_image.jpg";

        }
        
        public static function insert_ico(){


            $c = Company::get();

            $f = $c["favicon"];

            $path = ROOT."/favicon.ico";



            if($f and !DB::is_localhost()){


                $f = file_get_contents( Image::get($f) );
                          
                file_put_contents($path,$f);         
                

            } else {


                Files::remove($path);


            }


        }

        public static function is_image($ext){


            $ext = strtolower($ext);


            if(
                $ext == "png" or 
                $ext == "gif" or 
                $ext == "jpg" or 
                $ext == "jpeg"){

                    return true;

                }


            return false;

        }

        public static function get_main_image($image_arr){


            $ia = $image_arr;


            if(is_array($ia)){

                $ia = Format::current($ia);

                if(isset($ia["image"])){

                    $ia = $ia["image"];
                    
                    return $ia;

                }

            }

            return $ia;

        }


        public static function loader(){


            echo '
            <div class="loader">
            <img src="/assets/images/interface/spinner2.svg">
            </div>
            ';
            

        }

        
    }

?>