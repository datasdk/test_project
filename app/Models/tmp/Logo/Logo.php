<?php

    namespace App\Models\Logo;

    use App\Models\Cloudinary\Cloudinary;

    use DB;
    use Company;
    use Languages;


    class Logo{


        public static function insert($width=false, $height=false,$include_image = true){



            $result = Company::first();


            if(!$result){ return false; }



            $logo_image_ref_id = $result->logo_image_ref_id;


            if($logo_image_ref_id){


                $logo = Cloudinary::get($logo_image_ref_id, $width, $height);
            

               // file_put_contents("assets/images/website/logo.png", $content);

                if($include_image){


                    $lang = Languages::lang_url();

                    $url = "/";
                

                    $logo = "
                    <a href='". $lang . $url."' class='logo'>
                    <img src='".$logo."'  alt='".$_SERVER["HTTP_HOST"]."'>
                    </a>";  
    

                }
                

            } 
            

            if(empty($logo)){


                //$company = Company::get();

                //$logo = "<h3>".$company["company"]."</h3>";      

                return false;

            }
            
            
            return $logo;


        }


    }

?>