
<?php

    namespace App\Models\Api\Api;


    Class Gallery{


        public static $gallery;


        public static function load(){


            $mysqli = DB::mysqli();

            $gallery = array();
            
            

            $sql = "
            select * from gallery 
            
            inner join gallery_images
            on gallery.id = gallery_images.gallery_ref_id

            where gallery.active = 1";
            

            $result = mysqli_query($mysqli,$sql);

            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


                $gallery[$row["object_ref_id"]]["name"] = $row["name"];
                $gallery[$row["object_ref_id"]]["description"] = $row["description"];
                $gallery[$row["object_ref_id"]]["date"] = $row["date"];

                $gallery[$row["object_ref_id"]]["image"][$row["id"]]["image_ref_id"] = $row["image_ref_id"];


            }


            self::$gallery = $gallery;

        }



        public static function insert($arr){


            if(empty(self::$gallery)){

                self::load();

            }


            $object_ref_id = 0;


            extract($arr);
         

            if(!$object_ref_id){ return false; }


            $gallery = self::$gallery;

         

            echo '<div  class="gallery_wrapper" >';


                if(empty($gallery[$object_ref_id])){


                    echo "<div class='gallery-no-images'>Der er ingen billeder i dette galleri</div>";


                }

                else 
                
                {
                        
                        
                    $arr            = $gallery[$object_ref_id];

                    $name           = Sentence::get($arr["name"]);

                    $id             = sha1($name);

                    $description    = Sentence::get($arr["description"]);
                        
                    $date           = $arr["date"];



                    echo "<h1>".$name."</h1>";

                    echo "<p>".$description."</p>";



                    echo '<div id="gallery_'.$id.'" class="gallery_content">';


                        foreach($arr["image"] as $arr2){


                            $image_ref_id   = $arr2["image_ref_id"];
                            


                            $image = Cloudi::get($image_ref_id,false,false,false);
                            $thumb = Cloudi::get($image_ref_id,273,false,false);


                            if(empty($image_ref_id)){ continue; }


                                echo '
                                <img 
                                alt="'.$name.'"
                                src="'.$thumb.'"
                                data-image="'.$image.'"
                                data-description="'.$description.'"
                                >
                                ';


                        }
                        

                    echo '</div>';


                }
                

            echo '</div>';


        }

    }

?>