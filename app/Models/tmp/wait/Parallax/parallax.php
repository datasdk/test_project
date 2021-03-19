<?php

    namespace App\Models\Parallax\Parallax;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    Class Parallax extends Model{


        public static function insert($arr = []){


            $image = 0;
            $black = true;
            $image_ref_id = 0;
            $object_ref_id = 0;
            $text_object_ref_id = 0;
            $overlay = 1;
            $text = 0;
            


            extract($arr);


            if(!$object_ref_id){ return false; }

         
            $params = Frontend::get_parameter($object_ref_id);
        


            if(!empty($params)){

                $image_ref_id = $params["image_ref_id"];

                $text_object_ref_id = $params["text_object_ref_id"];

            }

            


            if(empty($image_ref_id)){

                $image = "/assets/images/interface/no_image.jpg";

            } else {

                $image = Cloudi::get($image_ref_id);

            }

            
            if(!$text){

                $text = Text::insert(["object_ref_id"=>$text_object_ref_id]);

            }
            

       
          

            echo '<div class="parallax">';


                echo '<div class="img-holder"   data-image="'.$image.'" ></div>';


                if($overlay){

                    echo "<div class='black'></div>";

                }
                                
            
            

                if(!empty($text)){

                    echo '<div class="parallax-text">'.$text.'</div>';

                }



            echo '</div>';


          


        }


    }


?>