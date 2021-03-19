<?php

   // use Google\Cloud\Translate\TranslateClient;
   namespace App\Models\Languages;


   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;

   use Session;
   

   
    class Languages extends Model{
        
        
        use HasFactory;


        public static $languages;

        public static $language_amount;

        public static $default_language;

        public static $language_options;

        

        public static $language_base_url;

        public static $tr;


     
  /*
        function __construct() {


            $languages = self::loads();

            if(empty($languages)){
        
                
                $sql = "
                INSERT INTO `languages` (`id`, `reference_code`, `image`, `language`, `valuta_ref_id`, `standard`, `active`) VALUES
                (1, 'da', 'denmark.svg', 'Danish', 0, 1, 1),
                (2, 'en', 'united-kingdom.svg', 'English', 0, 0, 0),
                (3, 'sw', 'sweeden.svg', 'Sweeden', 0, 0, 0),
                (4, 'nv', 'norway.svg', 'Norway', 0, 0, 0),
                (5, 'fr', 'france.svg', 'France', 0, 0, 0)
                ";
        
                DB::query($sql);
        
            }
    
        }
    */
        

        public static function loads(){

            

            if(!empty(self::$languages)){ return self::$languages; }



            $languages = array();
            


            

            $result = Languages::where("active",1);

            self::$language_amount = $result->count();


            foreach($result->get() as $val){


                if($val["standard"]){

                    self::$default_language = $val["id"];

                }
                

                $languages[$val["id"]] = 
                array("reference_code"=>$val["reference_code"],
                      "image"=>$val["image"],
                      "language"=>$val["language"],
                      "valuta_ref_id"=>$val["valuta_ref_id"],
                      "standard"=>$val["standard"],
                      "active"=>$val["active"]
                );


            }


           
            self::$languages = $languages;


            return self::$languages;


        }



        public static function get($language_ref_id = 0){


            if(empty(self::$languages)){ self::loads(); }
        

            $languages = self::$languages;

            $active_language = Session::get("active_language");



            if($active_language){


                return $active_language;


            } else {


                return self::get_default_language();


            }


        }



        public static function set($language_ref_id = 0){

            
            if(empty(self::$languages)){ self::loads(); }
        
            $languages = self::$languages;


            if(!$language_ref_id){

                $language_ref_id = self::get_default_language();

            }


            Session::set("active_language",$language_ref_id);


        }


        
        public static function get_default_language(){
            

            if(empty(self::$default_language)){ self::loads(); }

            return self::$default_language;


        }


        public static function get_code_by_language_id($language_ref_id = 0){

            
            $languages = self::$languages;
            

            if(!$language_ref_id){

                $language_ref_id = Languages::get();


            }


            if(isset($languages[$language_ref_id])){

                return $languages[$language_ref_id]["reference_code"];

            }

            return false;

        }


        
        public static function insert($props = []){

            $float = "left";

            extract($props);


            if(empty(self::$languages)){ self::loads(); }
        
            $languages = self::$languages;


            $url = $_SERVER["REQUEST_URI"]; 
            
            $base_url = Languages::$language_base_url;


     

/*
            if(isset($language_base_url[$url])){

                $base_url = ($language_base_url[$url]);

            } else {

                $base_url = $url;

            }
*/
            

            $active_language = Languages::get();



            if(count($languages) < 2){ return false; }

          

            $class = [];

            $class[]= "float_".$float;


            echo "<div class='language-wrapper ".implode(" ",$class)."'>";

            

                foreach($languages as $id => $val){
                    

                    $class = "";


                    if($active_language == $id){

                        $class = "active";

                    }


                    $image = "/assets/images/flags/".$val["image"];

                    $url = "/".$val["reference_code"]."/".$base_url;

                    $url = str_replace("//","/",$url);
                    

                    echo "<a href='".$url."' class='".$class."'>";
                    
                    echo "<img src='".$image."' alt='".$val["reference_code"]."' title='".$val["reference_code"]."'>";
                    
                    echo "</a>";


                }


            echo "</div>";


        }


        public static function lang_url($language_ref_id = 0){


            $language = Languages::loads();

            $lang = Languages::get_code_by_language_id($language_ref_id );

            $language_base_url = Languages::$language_base_url;



            if(count($language) > 1){

                return "/".$lang;

            }

            return "";

        }
        

        public static function is_default($language_ref_id){


            $default_language_ref_id = self::get_default_language();

            if($language_ref_id == $default_language_ref_id){

                return true;

            }

            return false;

        }

     


    }

?>