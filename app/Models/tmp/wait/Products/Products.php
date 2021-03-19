<?php

    namespace App\Models\Api\Api;

    
    class Products {

        
        public static $inventory;
        public static $parent;
        public static $result;
        public static $return_parents;
        public static $specifikations;
        public static $encode = array();
        public static $price;
        public static $accessories;
    
        
        public static function get($options = []){
            

            $products = "*";
            $filter = false; 
            $limit = 50;
            $order_by = false;

        

            extract($options);


            if(empty($products)){ return false; }

            // get inventory, reload if orderby is active

            $result = Shop::load_inventory($options);
 


            $return = array();

            
            // IN STOCK

            $in_stock = 1;

            if(isset($stock["in_stock"])){

                $in_stock = $stock["in_stock"];
                
            }
            
       
           
            if(isset($result["categories"]))
            foreach($result["categories"] as $val){


                
                if(isset($val["products"]))
                foreach($val["products"] as $product_ref_id => $val2){

        

                    if($products != "*"){


                        if(!is_array($products)){ $products = array($products); }
                        
                        if(!in_array($product_ref_id,$products)){

                            continue;

                        }


                    }


                    $return[$product_ref_id]= $val2;


                }


            }


            return $return;

        }

        

        public static function get_categories($product_ref_id){


            $sql = "select * from products_categories 
            where product_ref_id = '".$product_ref_id."'";


            $result = DB::select($sql);

         
            
            if(empty($result)){ return false; }

   
            $categories = array();

            foreach($result as $arr){

                $category_ref_id = $arr["category_ref_id"];

                $categories[$category_ref_id] = $arr["category_ref_id"];

            }


            return $categories;

        }


        public static function getParents($from_category_ref_id,$include_child = true,$use_col = "id"){


            if(is_array($from_category_ref_id)){ $from_category_ref_id = current($from_category_ref_id); }


            $seek_for_id = array($from_category_ref_id);


            $parent_ids = array();



            if(empty(self::$result)){

                $sql = "select * from categories order by parent_id";

                self::$result = DB::select($sql);

            }
            
  

            foreach(self::$result as $arr){

                
                $add = false;


                if($include_child)
                if($from_category_ref_id == $arr["id"]){ $add = true; }
                

                if(!$add)
                if(in_array($arr["parent_id"],$seek_for_id)){ $add = true; }

             

                if($add){


                    $seek_for_id[]= $arr["id"];
                 
                    $parent_ids[]= $arr[ $use_col ];

                }


            }


            return $parent_ids;


        }


        public static function has_variants($product_ref_id){

            
            $sql = "
            select * from products_prices 
            where 
            product_ref_id = '".$product_ref_id."' and
            variant_ref_id != 0";

            $numrows = DB::numrows($sql);


            if($numrows > 1){

                return true;

            } 

            return false;

        }


        public static function get_variants($product_ref_id){


            $sql = "
            select *,
            products_prices.id as id,
            variant_categories.name as categories_name,
            variants.name as variant_name

            from products_prices 

            inner join variants
            on products_prices.variant_ref_id = variants.id

            inner join variant_categories
            on variant_categories.id = variants.variant_category_ref_id 
            
            where 
            products_prices.product_ref_id = '".$product_ref_id."'";


            $select = DB::select($sql);


            $variants = [];

            foreach($select as $val){


                $variants[$val["variant_category_ref_id"]]["name"] = Sentence::get($val["categories_name"]);
                $variants[$val["variant_category_ref_id"]]["variants"][$val["variant_ref_id"]] = Sentence::get($val["variant_name"]);

           


            }

            return $variants;

        }


        public static function get_variants_by_id($arr){


            
            if(empty(self::$variants)){



                $mysqli = DB::mysqli();

                $variants = array();


                $sql = "
                select *,
                variants.id as variant_id
                from variants 
                
                inner join sentence
                on variants.name = sentence.group_id
                ";

                $result = mysqli_query($mysqli,$sql);
           
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                    $variants[$row["variant_id"]] = $row["content"];

                }


                self::$variants = $variants;


            }


            if(!is_array($arr)){

                $arr = [$arr];

            }
            

            $return = array();


            foreach($arr as $variant_ref_id){


                if(isset(self::$variants[$variant_ref_id])){

                    $return[]= self::$variants[$variant_ref_id];

                }

            }

            
            if(!empty($return)){

                return $return;

            }


            return false;


        }



        public static function remove($product_ref_id){



            $sql = "delete from products where id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_customer_groupes where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_downloads where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


           // $sql = "delete from products_seo where product_ref_id = '".$product_ref_id."'";
           // DB::delete($sql);


            $sql = "delete from products_reviews where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_accessories where main_product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_images where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_categories where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


            $sql = "delete from products_prices where product_ref_id = '".$product_ref_id."'";
            DB::delete($sql);


        }



       

        
        public static function specifications($product_ref_id = 0){


            if(empty(self::$specifikations)){


                $category = [];
                $specification = [];


                $sql = "select * from products_specifications";
                $result = DB::select($sql);


                $sql = "select * from specifications_categories";
                $sc = DB::select($sql);


                $sql = "select * from specifications";
                $sp = DB::select($sql);


//sa($result);
    
                foreach($result as $val){


                    if(isset($sc[$val["specification_category_ref_id"]])){

                        $category = Sentence::get( $sc[$val["specification_category_ref_id"]]["name"] );
                            
                    }
    
    
                    if(isset($sp[$val["specification_ref_id"]])){
                        
                        $specification = Sentence::get( $sp[$val["specification_ref_id"]]["name"] );
    
                    }



                    $specificationARR[$val["product_ref_id"]][$val["id"]] = array("category"=>$category,
                                                                                  "specification"=>$specification
                                                                                );

                }

            }



            if(!$product_ref_id){

                return $specificationARR;

            }

            if(isset($specificationARR[$product_ref_id])){

                return $specificationARR[$product_ref_id];

            }

    
            return false;


        }



        public static function accessories($product_ref_id){


            $accessorie = array();
            

            if(empty(self::$accessories)){


                $sql = "select * from products_accessories";

                $result = DB::select($sql);


                foreach($result as $val){

                    $accessorie[$val["main_product_ref_id"]][$val["product_ref_id"]] = $val["product_ref_id"];

                }

                 
                self::$accessories = $accessorie;

            }


  


            if(isset($accessorie[$product_ref_id])){
                
                
                $return = array();


                $pa = array();

                $sql = "select * from products_accessories where main_product_ref_id = '".$product_ref_id."'";

                $result = DB::select($sql);

/*
                foreach($result as $val){

                    $pa[$val["product_ref_id"]] = $val["amount"];

                }
*/
               

                $a = $accessorie[$product_ref_id];

                $p = Products::get(["products" => $a]);


                foreach($p as $id => $val){

                    $return = $p;
                    //$return[$id]["amount"] = $pa[$id];

                }


                return $return;

            }


            return false;

        }


       
        public static function get_id_by_reference_name($reference_name){

            
            $sql = "
            select * from products 
            where reference_name = '".$reference_name."'";


            $result = Format::current( DB::select($sql) );


            $result = $result["id"];

            return $result;

        }


        public static function add_to_cart($options = []){


            $product_ref_id = 0;

            $amount = 1;
            
            $variants = [];


            extract($options);


            return include(__DIR__."/includes/add_to_cart.php");


        }


    }
   

?>