<?php

    namespace App\Models\Text;
    

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    use Components;
    use Sentence;


    class Text extends Model{

        
        use HasFactory;


        public static function insert($arr = []){


            $p = 0;
            $pr = 0;
            $pl = 0;
            $pt = 0;
            $pb = 0;

            $object_ref_id = 0;
            $singleline = false;


            extract($arr);

        

            if(empty($object_ref_id)){ return false; }


            

            $content = Components::get_parameter($object_ref_id,"content");

            $content = urldecode( Sentence::get($content) );



            $str = $content;


            return $str;


        }



        public static function limited($text,$limit = 200){

            
            if(strlen($text) > $limit){ 

                

            }


            $str   = str_split($text, $limit);

            $first = array_shift($str);

            $rest  = implode("",$str);
            
            
            $content = "<span class='limited_text'>".$first."<span class='dots'>...</span><a href='javascript:'>".Sentence::translate("Read more")."</a><span class='hidden_text'>".$rest."</span></span>";

            return $content;

        }


        public static function get($name = ""){


            $object_ref_id = Components::set($name,"text");

            self::insert(["object_ref_id"=>$object_ref_id]);


        }

        

    }

?>