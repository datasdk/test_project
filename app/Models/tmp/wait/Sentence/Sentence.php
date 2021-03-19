<?php


    namespace App\Models\Sentence;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use DB;
    use Languages;

    class Sentence extends Model{
        

        use HasFactory;


        public static $text;

        public static $languages;

        public static $new_group_id;

        public static $default_language;

        public static $last_insert_id;

        public static $last_groupe_id;

        public static $languages_translations;

        public static $added_ids = array();

        public static $on_page_added_ids = array();



        public static function loads(){

            
            $text = array();
            
            if(empty(self::$text)){



                $result = self::where("type",1);

                
                foreach($result as $id => $val){


                    $text[ $val->language_ref_id ][ $val->group_id ] = $val;


                    self::$added_ids[]= $val->group_id;


                    if(self::$last_groupe_id < $val->group_id){

                        self::$last_groupe_id = $val->group_id;

                    }
                                      
                }

            }            
            
         
             // translations

             $languages_translations = array();


             $result = DB::table("sentences_translations")->get();
            
    
 
             foreach($result as $id => $val){
 
                 $languages_translations[ $val->key_id ][$val->language_ref_id ] = $val->content;
                 
             }
 
 
 
             // return
 
             self::$languages_translations = $languages_translations;
 


            self::$text = $text;


        }

        

        public static function set($content,$default_group_id = 0,$language_ref_id = false){

            
            self::loads();

            $group_id = $default_group_id;

            $content = Format::crypt($content);


            if(!$language_ref_id){

                $language_ref_id = Languages::get_default_language();

            }


            $sql = "
            select id from sentence 
            where 
            group_id = '".$group_id."' and 
            language_ref_id = '".$language_ref_id."'
            ";


            $num = DB::select($sql);




            if($num and $group_id){
            

                $sql = "
                update sentence 
                set content = '".$content."' 
                where 
                group_id = '".$group_id."' and
                language_ref_id = '".$language_ref_id."'";

                DB::update($sql);
                
               
            } else {


                
                $group_id = self::$last_groupe_id;

                
                $sentence_content = 
                array("content"=>$content,
                      "group_id"=>$group_id,
                      "language_ref_id"=>$language_ref_id,
                      "type"=>1
                     );


                     
                DB::insert("sentence",$sentence_content);

                self::mergeText($language_ref_id,$group_id,$sentence_content);
                      
                self::$last_groupe_id++;


            }


            return $group_id;
      

        }

        

        public static function mergeText($language_ref_id,$group_id,$sentence_content){


            if(!isset(self::$text[$language_ref_id][$group_id])){

                self::$text[$language_ref_id][$group_id] = [];

            }


            self::$text[$language_ref_id][$group_id] = 
            array_merge(self::$text[$language_ref_id][$group_id], $sentence_content);


        }



        public static function get($group_id,$language_ref_id = false){
            
          
            self::loads();


            if(!$group_id){

                return false;

            }
            

            


            if(empty(self::$text)){ self::loads(); }
          

            $text = self::$text;

      

            
            if(!$language_ref_id){ $language_ref_id = Languages::get(); }
            


            if(!isset($text[$language_ref_id][$group_id])){ 
               
                return false; 
            
            }

            
           

            $str = $text[$language_ref_id][$group_id];
          
           

            $url_encoded = 0;
            
            if(isset($str["url_encoded"])){ $url_encoded = $str["url_encoded"]; }

            $content = $str["content"];

            
            if($url_encoded){
           
                $content = Format::uncrypt($content);

            }
        

            return $content;


        }



        public static function set_by_post($post){


            $content = current($post);

            $group_id = key($post);

        
            
            $group_id = self::set($content,$group_id);

            return $group_id;


        }


    
        public static function set_input_name($name,$default_group_id = 0){


            $group_id = $default_group_id;

            

            if(!$default_group_id){ 
                

                if(in_array($group_id,self::$on_page_added_ids) or !$default_group_id){
                  

                    if(in_array($group_id,self::$added_ids) or !$default_group_id){
                     

                        self::$last_insert_id ++;
            
                        $group_id = self::$last_insert_id;
            
            
                        while(in_array($group_id,self::$added_ids)){
            
                            $group_id ++;
                
                        }
            
                    }
                    

                    self::$added_ids[] = $group_id;

                }
    
    
            }

            
    
            self::$on_page_added_ids[]= $group_id;

            return $name."[".$group_id."]";
             


        }


        public static function remove_duplicated($lang_ref_id){


            $r = DB::select("select * from sentences_translations");

            $a = [];
      

            foreach($r as $id => $v){


                $key = $lang_ref_id."__".$v["key_id"];

          
                if(!in_array($key,$a)){


                    $a[] = $key;


                } else {


                    $sql = "
                    delete from sentences_translations 
                    where 
                    id = '".$id."'";

                    DB::delete($sql);


                }


            }

        }

 


        public static function translate($content,$default_language_ref_id = 0){

            

            $key_id = sha1($content."_");

            
            if(empty(self::$languages_translations)){

                self::loads();

            }


            $from_language_ref_id = 2;


            $to_language_ref_id = Languages::get();

            $languages = Languages::loads();

      

            if(!$default_language_ref_id){

                $default_language_ref_id = Languages::get();

            } 
            
     
     

            // create translate if not exists

            foreach($languages as $language_ref_id => $val){


                if(!isset(self::$languages_translations[$key_id][$language_ref_id])){

          

                    $arr = [
                        "key_id"=>$key_id,
                        "content"=>$content,
                        "language_ref_id"=>$language_ref_id
                    ];
    

                    DB::insert("sentences_translations",$arr);

                    self::$languages_translations[$key_id][$language_ref_id] = $content;

                    
                }


            }




            if(isset(self::$languages_translations[$key_id][$default_language_ref_id])){

                return self::$languages_translations[$key_id][$default_language_ref_id];

            } else {

                return $content;

            }

            
        }

    }



?>