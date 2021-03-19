<?php

    namespace App\Models\Api\Api;

    class Promotion{
        
        
        public static function get($order_ref_id = 0){


            $order = Order::get($order_ref_id);


            if($order){

                $promotion_code = $order["promotion_code"];

                return $promotion_code;

            }


            return false;

        }


        public static function set($promotion_code,$order_ref_id = 0){


            if(!$order_ref_id){

               $order_ref_id = Order::get_order_id(); 

            }
            

            $order = Order::get();

            if(empty($order)){ return false; }


            // remove existing code
            self::remove($order_ref_id);

      

            if(!empty($promotion_code)){
             
                
                $mysqli = DB::mysqli();

                
                $delivery_price = 0;
                
                if(isset($order["delivery_price"])){ $delivery_price = $order["delivery_price"]; }
                

                $discount = 0;

            
                $sql = "
                select * from promotion_code 
                where
                    
                code = '".$promotion_code."' 
                and 
                ((".time()." < expiration_date) or (has_expiration_date = 0)) 
                and 
                ((max_use > used_amount) or (unlimited_use = 1))
                    
                and 
                active = 1
                limit 1";
                    
  
                $result = mysqli_query($mysqli,$sql);

               
                
                if(mysqli_num_rows($result) == 0){


                    $title = Sentence::translate("Invalid discount code");

                    $msg = Sentence::translate("The discount code does not exist");


                    return json_encode(array("success"=>false,"title"=>$title,"msg"=>$msg));


                } else {
                    
                    
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                        
                        
                        $code = $row["code"];	
                        $save_cash = $row["save_cash"];	
                        $save_procent = $row["save_procent"];	
                        $free_delivery = $row["free_delivery"];	
                        $max_use = $row["max_use"];	
                        $used_amount = $row["used_amount"];	
                        $has_expiration_date = $row["has_expiration_date"];	
                        $expiration_date = $row["expiration_date"];
                        $affect_reduced_goods = $row["affect_reduced_goods"];
                        

                        // importamt
                        // price must stand like this because "ignore_products_with_discount" will be
                        // set inside the promotion code validating
                        if(!isset($prices)){


                            $filter = false;

                            if(!$affect_reduced_goods){ $filter = "ignore_products_with_discount"; } 

                            $prices = Order::load_prices($order_ref_id,$filter);
                            
                            $subtotal = $prices["sum"];
                            

                        }
                        

                        // calculate discount
                        
                        $discount = $save_cash;
                        $discount += ($subtotal / 100 * $save_procent);
                        

                        if($free_delivery == 1){ $delivery_price = 0; }
                        

                        /*
                        GRATIS LEVERING SKAL IKKE MARKERES SOM RABAT
                        if(isset($delivery_price))
                        if($free_delivery){
                            
                            $discount += $delivery_price;	

                        }
                        */
                        
                        
                        $discount = round($discount);
                        
                    
                    }
                
                
                }


          

                $order = Order::get();


                if(!empty($order["promotion_code"])){


                    $title = Sentence::translate("Discount code already exists");

                    $msg = Sentence::translate("A discount code has already been entered for this order");

                    
                    return json_encode(array("success"=>false,"title"=>$title ,"msg"=>$msg));


                }




                
                $rabatkode = "";	


                // ORDERS

                $sql = "
                update orders set  
                free_delivery = '".$free_delivery."',
                promotion_code = '".$promotion_code."',
                discount = '".$discount."',
                delivery_price = '".$delivery_price."'
                where id = '".$order_ref_id."'";
               
                 
                DB::update($sql);
                

                // PROMOTION CODE

                $sql = "
                update promotion_code 
                set 
                used_amount = used_amount +1  
                where code = '".$promotion_code."'";

                DB::update($sql);
                

                $language_code = Language::get_code_by_language_id();


            
                return json_encode(array("success"=>true));


                exit();


            }


        }


        public static function update($order_ref_id = 0){

            if(!$order_ref_id)
            $order_ref_id = Order::get_order_id();


            $promotion_code = self::get($order_ref_id);


            self::set($promotion_code);


        }


        public static function remove($order_ref_id = 0){


            if(!$order_ref_id)
            $order_ref_id = Order::get_order_id();


            if(Order::exists($order_ref_id)){

                // ORDERS

                $sql = "
                update orders set  
                free_delivery = '0',
                promotion_code = '',
                discount = '0'
                where id = '".$order_ref_id."'";

                    
                DB::update($sql);

                return true;

            }
            
            return false;

        }

        
        public static function exists($promotion_code){


            $sql = "
            select * from promotion_code 
            where
                
            code = '".$promotion_code."' 
            and 
            ((".time()." < expiration_date) or (has_expiration_date = 0)) 
            and 
            ((max_use > used_amount) or (unlimited_use = 1))
                
            and 
            active = 1
            limit 1";
                

            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }

    }

?>