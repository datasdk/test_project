<?php

    namespace App\Models\Cloudinary;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    use DB;


    class Cloudinary extends Model{

        
        public static $images;


        public function __construct(){
                
            
            \Cloudinary::config(array( 
                "cloud_name" => env("cloud_name"),
                "api_key"    => env("api_key"), 
                "api_secret" => env("api_secret")
            ));


        }


        
        public static function upload($props = []){


            $file   = false;
            $name   = false;
            $width  = 1920;
            $height = 1080;
            $private = false;
            

            extract($props);


            
            if(empty(self::$cloud_name)){

                self::connect();

            }



            if(!$file){ return false; }
            if(!$name){ $name = uniqid(); }
            else{ $name = sha1($_SERVER['HTTP_HOST']."_".$name); }

               
            $ext = pathinfo($file, PATHINFO_EXTENSION); 

            
     

            $options = array(
               "public_id" => $name,
               "format" => $ext
            );



            if(!file_exists($file)){

                return ["success"=>false,"msg"=>"File does not exists"];

            }
            

            if(Image::is_image($ext)){


                $options["crop"] = "limit";

           
                if($width and $height){
                    
                    $options["width"]  = $width; 
                    $options["height"] = $height; 
                
                } 
        
            }
        
        

            try {
                

                $info = \Cloudinary\Uploader::upload($file , $options );


            } catch (Exception $e) {


                return ["success"=>false,"msg"=>$e->getMessage()];

           
            }

            
                
                
            return $info["url"];


        } 




      




        public static function get($image_ref_id = 0,$width = 0,$height = 0,$quality=0){



            if(!is_numeric($image_ref_id)){

                return $image_ref_id;

            }

            
            if(empty(self::$images)){

                self::loads();

            }

            
            $images = self::$images;

            
            if(!$image_ref_id){ return false; }
            

            if(!empty($image_ref_id))
            if(isset($images[$image_ref_id])){


                $arr = $images[$image_ref_id];

                $image  = $arr->image;
                
                
                $x1     = $arr->x1;
                $y1     = $arr->y1;
                $x2     = $arr->x2;
                $y2     = $arr->y2;


                

                
                $x2  = abs($x2 - $x1);
                $y2  = abs($y2 - $y1);

              
                return self::format($image,$width,$height,$quality,$x1,$y1,$x2,$y2);

            
            } else {

                return false;

            }


            return $images;


        }

        
        public static function size($src,$w=0,$h=0,$q=0){

            

            $sp = "/upload";

            $s = explode($sp,$src);

            $r = current($s);

            $r.= $sp . "/" ;


            if($w){ $op[]= "w_".$w; }
            
            if($h){ $op[]= "h_".$h; }


            if(!$q){ 
                
                $op[]= "q_auto";
                $op[]= "f_auto";
                
            
            } else {

                $op[]= "q_".$q;

            }

             

            $r.= implode(",",$op);

            $r .= end($s);

            return $r;


        }
      


        public static function smart_image($image,$width_height=100,$quality=0){

            $src = self::format($image,$width_height,$quality);

            return '<img src="" data-image="'.$src.'" class="smart_image">';

        }


        
/*
        public static function image_field($name,$callback_onchange="",$callback_finish="",$reset_when_finished=false){

            echo '
            <input type="file" name="upload" class="cloudinary" 
            data-callback_onchange="'.$callback_onchange.'"
            data-callback_finish="'.$callback_finish.'"
            data-folder_ref_id="0"
            data-reset_when_finished="'.intval($reset_when_finished).'"
            accept="image/x-png,image/gif,image/jpeg" 
            >';

        }
     */   

    }		

?>