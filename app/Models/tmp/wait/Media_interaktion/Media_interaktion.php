
<?php

    namespace App\Models\Api\Api;


    class Media_interaktion{


    
        public static function get($media_ref_id, $customer_ref_id){

      
            $sql = "
            select * from media_interaktions 
              
            where 
            media_ref_id = '".$media_ref_id."' and
            customer_ref_id = '".$customer_ref_id."' and 
            active = 1
            ";

        

            return Format::current( DB::select($sql) );

        }



        public static function set($arr){

            $media_ref_id = false;
            $customer_ref_id = false;
            $stars = false;
            $share = false;

            extract($arr);


            $stars = intval($stars);
            $share = intval($share);
         

            if(!$media_ref_id or !$customer_ref_id){ return false; }



            if(self::exists($media_ref_id, $customer_ref_id)){


                $upd = [];

                
                if($stars){ $upd[]= "stars = '".$stars."'"; }
                if($share){ $upd[]= "share = '".$share."'"; }


                if(empty($upd)){ return false; }



                $sql = "
                update media_interaktions 
                set 
                ";


                $sql .= implode($upd,",");
                

                $sql .= "
                where 
                media_ref_id = '".$media_ref_id."' and
                customer_ref_id = '".$customer_ref_id."'
                ";

      
                DB::update($sql);




            } else {


                $arr = array("media_ref_id"=>$media_ref_id,
                             "customer_ref_id"=>$customer_ref_id,
                             "stars"=>$stars,
                             "share"=>$share,
                             "date"=>time(),
                             "visible"=>1,
                             "active"=>1
                            );

                DB::insert("media_interaktions",$arr);


            }



        }


        public static function exists($media_ref_id, $customer_ref_id){

            
            $sql = "
            select * from media_interaktions 
            where 
            customer_ref_id = '".$customer_ref_id."' and 
            media_ref_id = '".$media_ref_id."' and 
            active = 1
            ";
            

            if(DB::numrows($sql)){

                return true;

            }


            return false;

        }
        

    }

?>
