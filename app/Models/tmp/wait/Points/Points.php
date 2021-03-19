<?php


    namespace App\Models\Api\Api;

    
    Class Points{


        public static function get($name = "*",$customer_ref_id = 0){


            $mysqli = DB::mysqli();


            $sql = "
            select *,
            customers_points.id as id
            from points 

            inner join customers_points 
            
            on points.id = customers_points.point_ref_id
            ";


            if($name != "*"){

                $sql .= " and points.name = '".$name."' ";

            }


            if($customer_ref_id){

                $sql .= " and customers_points.customer_ref_id = '".$customer_ref_id."'";

            }


        

           return DB::select($sql);



        }



        public static function get_total_points($customer_ref_id){


            if(empty($customer_ref_id)){ return false; }


            $sql = "
            select *,
            customers_points.id as id
            from points 

            inner join customers_points 
            
            on points.id = customers_points.point_ref_id
         
            where customer_ref_id = '".$customer_ref_id."'";


            $points = 0;

            $arr = DB::select($sql);


            foreach($arr as $val){

                $points += $val["points"];

            }


            return $points;


        }


      


        public static function add($name,$customer_ref_id){

            
            $point_ref_id = self::create_point($name);

            if(!$point_ref_id){ return false; }



            if(!self::can_be_duplicated($point_ref_id)){

                return false;

            }


            $arr = 
            array("customer_ref_id"=>$customer_ref_id,
                  "point_ref_id"=>$point_ref_id
                );


            return DB::insert("customers_points",$arr);


        }



        public static function remove($name,$customer_ref_id = 0){


            $sql = "
            delete from customers_points where name = '".$customer_ref_id."' ";


            if($customer_ref_id){

                $sql .= " and customer_ref_id = '".$customer_ref_id."' ";

            }


            DB::remove($sql);


        }


        public static function remove_all($customer_ref_id){


            $sql = "delete from customers_points where customer_ref_id = '".$customer_ref_id."' ";

            DB::remove($sql);


        }


        // create points and categories

        private static function create_point($name,$description = "",$points=1,$category_ref_id=0,$can_be_duplicated=1){


            $sql = "select * from points where name = '".$name."' limit 1";

            $r =  DB::select($sql);


            if(!empty($r)){


                return current(array_keys($r));


            } else {


                $arr = array("category_ref_id"=>$category_ref_id,
                             "name"=>$name,
                             "description"=>$description,
                             "points"=>$points,
                             "date"=>time(),
                             "can_be_duplicated"=>$can_be_duplicated
                            );

                return DB::insert("points",$arr);

            }


        }


        public static function get_category($name){


            $sql = "select * from points_category where name = '".$name."' limit 1";

            $r =  DB::select($sql);


            if($r){

                return current(array_keys($r));

            }


            return false;

        }


        public static function create_category($name,$description = ""){



            $sql = "select * from points_category where name = '".$name."' limit 1";

            $r =  DB::select($sql);

         

            if(!empty($r)){

               
                return current(array_keys($r));


            } else {
            

                $arr = array("name"=>$name,
                             "description"=>$description,
                             "date"=>time()
                            );

                return DB::insert("points_category",$arr);

            }


        }


        private static function can_be_duplicated($point_ref_id){

            $sql = "
            select * from points 
            where 
            id = '".$point_ref_id."' and 
            can_be_duplicated = 1";


            // if there is points there cant be duplicated. stop!
            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }


    }

?>