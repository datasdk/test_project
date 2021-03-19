<?php

    namespace App\Models\Socialmedia;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    

    use DB;


    class Socialmedia extends Model{

        use HasFactory;

        public static $social_media;


        public static function loads(){




            $social_media = array();

            $sql = "select * from frontend_social_medias order by sorting";

            $result = DB::select($sql);


            foreach ($result as $row){
                
                $social_media[$row->id]["image"] = $row->image;
                $social_media[$row->id]["name"]  = $row->name;
                $social_media[$row->id]["link"]  = $row->link;  
                $social_media[$row->id]["symbol"]  = $row->symbol;     
                
            }

            
            self::$social_media = $social_media;

        }


        public static function insert($arr = []){


            $show_images = true;


            extract($arr);


            if(empty(self::$social_media)){ self::loads(); }


            ob_start();
            

                echo "<div class='social_media_wrapper'>";


                    foreach(self::$social_media as $id => $val){

                        if(empty($val["link"])){ continue; }


                        if($show_images){

                            $img = "<img src='/assets/images/social_medias/".$val["image"]."'  alt='".$_SERVER["HTTP_HOST"]."'>";

                        } else {

                            $img = '<i class="fab fa-'.$val["symbol"].'"></i>';

                        }
                        

                        echo "<a href='".$val["link"]."' target='_blank'>";
                        
                            echo $img;

                        echo "</a>";

                    }


                echo "</div>";


            $content = ob_get_contents();

            ob_end_clean();


            return $content;

            

        }



        public static function share_facebook(){
            

            $url = "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

            echo '
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/da_DK/sdk.js#xfbml=1&version=v3.2";
            fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));</script>

            <div class="fb-share-button" data-href="'.$url.'" data-layout="button" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Del</a></div>
            ';

        }


        public static function share_google(){

            echo '<div class="g-plus" data-action="share"></div>';

        }



    }

?>