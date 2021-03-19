<?php

    class Stock{


        public static $stock = array();
        

        public static function load(){
            

            $enable_stock = Settings::get("enable_stock");

            $result = DB::select("select * from stock");

            $stock = array();


            foreach($result as $arr){

                
                if(!isset($stock[$arr["product_ref_id"]]["in_stock"])){

                    $stock[$arr["product_ref_id"]]["in_stock"] = 0;

                }
                
            
             

                $stock[$arr["product_ref_id"]]["stock"][$arr["sku_key"]] = 
                array("sku_key" => $arr["sku_key"],
                      "product_ref_id" => $arr["product_ref_id"],
                      "amount" => $arr["amount"],
                      "unlimited" => $arr["unlimited"]
                    );


        


                // hvis lagerstyring ikke er sat, skal varen være tilgængelig

                if($arr["amount"] > 0 or $arr["unlimited"] or !$enable_stock){

                    $stock[$arr["product_ref_id"]]["in_stock"] = 1;

                }
             
            }


            self::$stock = $stock;

        }



        public static function in_stock($product_ref_id = 0){


            //if(!Layout::get("stock")){ return true; }



            if(empty(self::$stock)){ self::load(); }


            $stock = self::$stock;


            if(!isset($stock[$product_ref_id])){

                return false;

            }


            return $stock[$product_ref_id]["in_stock"];

        }



        public static function in_stock_by_sku($order_ref_id,$sku,$amount,$include_cart = true){


           // if(!Layout::get("stock")){ return true; }

            if(empty(self::$stock)){ self::load(); }
            
            if(!$order_ref_id){ $order_ref_id = Order::get_order_id(); }

            
            $cart_amount = 0;


            // hvis ordre ikke findes, skal kurven ikke sammenlignes
            if($order_ref_id)
            if($include_cart){


                $sql = "
                select amount from order_products 
                where 
                key_id = '".$sku."' and 
                order_ref_id = '".$order_ref_id."'
                limit 1";

                $result = DB::select($sql);


                if($result){

                    $cart_amount = current($result)["amount"];

                }
                
            }



            $sql = "
            select id from stock 
            where sku_key = '".$sku."' and (amount >= ".($cart_amount + $amount)." OR unlimited = 1)";

            $num = DB::numrows($sql);
            
     

            if($num > 0){ 
                
                return true; 
            
            }


            return false;


        }



        public static function get($product_ref_id = 0){

           // if(!Layout::get("stock")){ return true; }
            if(empty(self::$stock)){ self::load(); }


            $stock = self::$stock;

            
            if($product_ref_id){

                if(isset($stock[$product_ref_id])){

                    return $stock[$product_ref_id];
    
                } else {

                    return false;

                }

            }
            

            return $stock;

        }



        public static function get_sku_key($product_ref_id,$variant_array){

       

            if(empty($variant_array)){


                $sku_key = sha1($product_ref_id);


            } else {


                if(!is_array($variant_array)){ $variant_array = array($variant_array); }

                sort($variant_array);

                $sku_key = sha1($product_ref_id."_".implode("_",$variant_array));
                

            }



            $sql = "select id from stock where sku_key = '".$sku_key."'";

            $numrows = DB::numrows($sql);

  
            if($numrows){


                return $sku_key;


            } else {


                return false;


            }


        }


        public static function get_stock_id($product_ref_id,$variant_array){

            
            $sku_key = self::get_sku_key($product_ref_id,$variant_array);
            
            
        
            $sql = "select * from stock where sku_key = '".$sku_key."'";

            $result = DB::select($sql);


            if(empty($result)){ return 0; }


            $result = current($result);

            return $result["id"];


        }


        public static function is_picked($order_ref_id){

            $order = Order::get($order_ref_id);   

            if(empty($order) or $order["order_done"]){

                return false;

            }

        }



        public static function pick($order_ref_id){

            self::update_stock($order_ref_id,"pick");

        }


        public static function refund($order_ref_id){

            self::update_stock($order_ref_id,"refund");

        }


        public static function create_stock($order_ref_id,$type){




        }

        public static function product_is_picked($order_ref_id,$stock_ref_id){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
            
            if(empty($order_ref_id)){ return false; }


            $sql = "
            select * from order_products 
            where 
            order_ref_id = '".$order_ref_id."' and
            stock_ref_id = '".$stock_ref_id."' and 
            is_picked = 1";
        
            $num = DB::numrows($sql);

            if($num){ return true; }


            return false;

        }


        public static function order_set_picked_to($order_ref_id,$stock_ref_id,$stock_picked){


            if(!$order_ref_id){ $order_ref_id = Order::get_order_id(); }

            if(empty($order_ref_id)){ return false; }
        

            $sql = "
            update order_products 
            set 

            is_picked = '".$stock_picked."' 
            
            where
            order_ref_id = '".$order_ref_id."' and
            stock_ref_id = '".$stock_ref_id."'";
        
            DB::update($sql);

            return true;
        
        }



        public static function update_stock($order_ref_id,$type = "pick"){


            if(!in_array($type,["pick","refund"])){ return false; }


            $products = Order::load_products(["order_ref_id"=>$order_ref_id]);

            if(empty($products)){ return false; }

            
            $stock_products = array();


            // IF TYPE IS PICK OR REFUND
            if($type == "pick"){   $calculation = "-"; }
            if($type == "refund"){ $calculation = "+"; }


            

            if(isset($calculation)){


                $sql = "";

            
                foreach($products as $key_id => $arr){
    

                    $stock_ref_id = $arr["stock_ref_id"];
    
                    $amount = abs($arr["amount"]);
    
    
                    if($stock_ref_id and $amount > 0){
    


                        $is_picked = self::product_is_picked($order_ref_id,$stock_ref_id);

    

                        if($is_picked  and $type == "pick"){   continue; }
                        if(!$is_picked and $type == "refund"){ continue; }



                        $sql = "
                        update stock 
                        SET amount = (amount ".$calculation." ".$amount.") 
                        where 
                        id = '".$stock_ref_id."' and
                        unlimited = 0";

                        DB::update($sql);
                      


                        if($type == "pick"){   $stock_picked = 1; }
                        if($type == "refund"){ $stock_picked = 0; }


                        self::order_set_picked_to($order_ref_id,$stock_ref_id,$stock_picked);

    
                    }
    
    
                }


            }
            


            return true;

        }
 

        
        public static function set_stock_request($order_ref_id,$stock_products){



            $sql = "select id from stock_request where order_ref_id = '".$order_ref_id."'";


            if(!DB::numrows($sql)){


                $arr = ["order_ref_id" => $order_ref_id,
                        "date" => time(),
                        "is_refunded" => 0,
                        "order_done" => 0
                      ];

                $stock_request_ref_id = 
                DB::insert("stock_request",$arr);


            } else {


                $stock_request_ref_id = 
                self::get_stock_request_by_order_id($order_ref_id);


            }


            if(!is_array($stock_products)){

                $stock_products = [$stock_products];

            }



            foreach($stock_products as $stock_ref_id => $amount){

  

                $sql = "
                select id from stock_request_products 
                where 
                stock_ref_id = '".$stock_ref_id."' and 
                stock_request_ref_id = '".$stock_request_ref_id."'";

                
     


                if(!DB::numrows($sql)){

                    
                    $arr = ["stock_request_ref_id" => $stock_request_ref_id,
                            "stock_ref_id" => $stock_ref_id,
                            "amount" => $amount
                            ];


                    DB::insert("stock_request_products",$arr);



                } else {


                    $sql = "
                    update stock_request_products
                    set

                    amount = '".$amount."'

                    where stock_ref_id = '".$stock_ref_id."'
                    ";

                   
                    DB::update($sql);


                }


            }
            

        }



        public static function get_stock_request_by_order_id($order_ref_id = 0){

            $sql = "select * from stock_request where order_ref_id = '".$order_ref_id."'";

            $r = Format::current( DB::select($sql) );


            return $r["id"];

        }


        public static function active_stock_request($order_ref_id = 0){


            if(!$order_ref_id){ $order_ref_id = Order::get_order_id(); }

            if(!$order_ref_id){ return false; }



            self::get_stock_request_by_order_id($order_ref_id);



            $sql = "
            update stock_request 
            set order_done = 1 
            where 
            order_ref_id = '".$order_ref_id."'";

            DB::update($sql);


        }


    }    


?>