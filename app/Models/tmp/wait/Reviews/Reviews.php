<?php

    namespace App\Models\Reviews;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    use DB;
    use Sentence;


    class Reviews extends Model{
    
        use HasFactory;

        public static $review;


        public static function loads(){


            $sql = "select * from reviews where active = 1 order by stars desc,RAND()";
            
            $result = DB::select($sql);

            $review = array();


            foreach($result as $val){


                $arr = array();
                
                $image_url = false;

                $image_ref_id = $val->image_ref_id;


                if($image_ref_id){


                    if($val->image_ref_id){

                        $image_url = Cloudi::get($val->image_ref_id,100,100);
                        
                    }


                }
                              
               


                $arr["id"] = $val->id;
                $arr["object_ref_id"] = $val->object_ref_id;
                $arr["image"] = $image_url;
                $arr["customer"] = Sentence::get($val->customer);
                $arr["content"] = Sentence::get($val->content);
                $arr["stars"] = $val->stars;
                $arr["active"] = $val->active;


                $review[$val->object_ref_id][$val->id] = $arr;



            }


            self::$review = $review;

        }


        public static function insert($arr = []){

  
            extract($arr);


            if(empty($object_ref_id)){ return false; }
            

            if(empty(self::$review)){ self::loads(); }
            
            $review = self::$review;

          

            if(isset($review[$object_ref_id])){
                
                include(__DIR__."/includes/standard.php");

            }


            
            return false;

        }


    }

?>