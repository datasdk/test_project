<?php
    
    namespace App\Models\Api\Api;

    
    class Search{


        public static $current_search_word;


        public static function bar(){

            include(__DIR__."/includes/search_field.php");

        }


        public static function get_search_word(){

            if(!empty(self::$current_search_word)){

                return self::$current_search_word;

            }

            return false;

        }


        public static function is_search(){

            if(self::get_search_word()){

                return true;

            }

            return false;

        }


        public static function get_product_id_by_search($search = 0){


            if(!$search){

                $search = self::$current_search_word;

            }

            
        


            if(empty($search)){ return false; }



            $mysqli = DB::mysqli();


            $products = array();


            $sql = "
            select * from products_search 
            
            inner join sentence
            on products_search.search_word = sentence.group_id

            where content like '%".$search."%' group by product_ref_id";

            

            $result = mysqli_query($mysqli,$sql);
            
            if(mysqli_num_rows($result) == 0){

                return false;

            }

            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
    
                $products[]= $row["product_ref_id"];
    
            }


            return $products;

        }
    
    }



?>