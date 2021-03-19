<?php

    namespace App\Models\Pages;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
   
    use Request;
    use Languages;


    class Pages extends Model{
        
        use HasFactory;

               
        public static function replace(){


            $url = "/".Request::path();

            $url = str_replace("//","/",$url);
         
            $url = str_replace(Languages::lang_url(),"",$url);
 
            if(empty($url)){ $url = "/"; }
         
            return $url;


        }


    }

?>