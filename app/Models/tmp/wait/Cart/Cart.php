<?php

    namespace App\Models\Cart\Cart;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    class Cart extends Model{

        
        public static $products;



        public static function insert($arr = []){


            $type = "editable";
            
            $order_ref_id = 0;

            $showcase = true;

            $promotioncode = true;

            $redirect = false;
            
            $header = true;

            $has_products = true;

            $booking = true;

            $hide_completed = false;

            $button = true;


            extract($arr);



            $enable_shop = Settings::get("enable_shop");   

            $shop_cart = Layout::get("shop_cart");


            
            if($redirect)
            if(!$enable_shop){

                header("location: /");

                exit();    
        
            }

       
            
            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
            
            
            if($has_products){


                if(empty(self::$products)){


                    $p = [
                          "order_ref_id"=>$order_ref_id,
                          "hide_completed"=>$hide_completed
                        ];


                    self::$products = Order::load_products($p);

                }
                
                $products = self::$products;
                

            }
            


                        
            // VIGTIGT !! alle disse typer kurve skal ud af empty($products) .. men vær omhyggelig
            // det kan være der meldes fejl et sted
                    

            if($type == "editable"){    include(__DIR__."/includes/editable/editable.php"); }

            if($type == "checkout"){    include(__DIR__."/includes/checkout/checkout.php"); }

            if($type == "overview"){    include(__DIR__."/includes/overview/overview.php"); }
                
            


            if($shop_cart)
            if($type == "icon"){        include(__DIR__."/includes/cart_icon/cart_icon.php"); }
            
            

        }


      


        public static function overview($arr = []){

            
            $arr["type"] = "overview";

            self::insert($arr);

        }


        public static function checkout($arr = []){

            
            $arr["type"] = "checkout";

            self::insert($arr);

        }


        public static function icon($arr = []){
            
            $arr["type"] = "icon";

            self::insert($arr);

        }



        public static function editable($arr = []){
            
            $arr["type"] = "editable";

            self::insert($arr);

        }




        public static function amount($option = []){


            $order_ref_id = 0;


            extract($option);


            if(!$order_ref_id){
                
                $order_ref_id = Order::id();

            }


            if(!$order_ref_id){ return 0; }

            
            $sql = "
            select amount from 
            order_products 
            where 
            order_ref_id = '".$order_ref_id."'";


            $result = DB::select($sql);


            if(!$result){ return 0; }


            
            $amount = 0;


            foreach($result as $val){

                $amount += $val["amount"];

            }


            return $amount;


        }



        public static function empty($order_ref_id = 0){

            
            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
            

            $sql = "
            delete from 
            order_products 
            where 
            order_ref_id = '".$order_ref_id."'";


            return DB::delete($sql);


        }


        public static function is_empty($order_ref_id = 0){

          
            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
        
            if(!$order_ref_id){

                return true;

            }


            $sql = "
            select id 
            from order_products 
            where order_ref_id = '".$order_ref_id."'";
           

            if(DB::numrows($sql)){

                return false;

            }

        
            return true;

        }



        public static function in_cart($product_ref_id,$order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }


            if(!$order_ref_id){ return false; }
             


            $sql = "
            select * from order_products 
            where 
            product_ref_id = '".$product_ref_id."' AND 
            order_ref_id = '".$order_ref_id."'";
            
            $result = DB::select($sql);


            if(!$result){

                return false;

            }


            $result = Format::current($result);


            return $result;


        }


        public static function get_change_amount_url($key_id,$amount){

            return  "javascript: api_request(\"/cart/change\",{ key_id: \"".$key_id."\" , amount:\"".$amount."\" },\"post\",\"array\")";
        
        }

    }

?>