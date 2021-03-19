<?php

    Class Shipping{


        public static $client;


        public static function load(){


            if(!empty(self::$client)){ return self::$client; }


            $api_user = "0b9f25d6-dd63-498a-a6da-da442750d537";
            
            $api_key  = "815cee00-394d-491c-badd-064ef1d5f4d4";


            try {
              
                self::$client = new Shipmondo($api_user, $api_key);
            
            } catch (ShipmondoException $e) {
                
                echo $e->getMessage();
            
            }

            return self::$client;

        }


        public static function get(){


            $sql = "select * from shipping where active = 1";

            $result = DB::select($sql);

            return $result;


        }



        public static function test(){


            $client = self::load();
            
      
            $params = [
                'id' => 95060,
                'carrier_code' => 'gls'
            ];
            
   

            return $client->getPickupPoints($params);

        }
      

        public static function pickup_points($address,$zipcode,$carrier_code,$country_code="DK",$id=false){

         
            $client = self::load();
           

            if(
                !$address or
                !$country_code or
                !$carrier_code or
                !$zipcode
            ){ return false; }
      

            $params = [
                'address' => $address,
                'country_code' => $country_code,
                'carrier_code' => $carrier_code,
                'zipcode' => $zipcode
            ];
            
          

            $result = $client->getPickupPoints($params);
            

          


            if($id){

                foreach($result as $val){

                    if($val["id"] == $id){

                        return $val;

                    }

                }

            }


            return $result;

        }


        public static function createShipment(){



        }
        

        public static function is_shipping($delivery_type){
            
            

            if(empty($delivery_type)){ return false; }


            $shipping = ["GLSDK_SD","GLSDK_HD","GLSDK_BP","PDK_PPR","PDK_BP"];


            if(in_array($delivery_type,$shipping)){

                return true;

            }

            return false;

        }


        public static function get_carrier_by_code($delivery_type){


            $gls = ["GLSDK_SD","GLSDK_HD","GLSDK_BP"];

            $postnord = ["PDK_PPR","PDK_BP"];


            if(in_array($delivery_type, $gls)){

                return "gls";

            }


            if(in_array($delivery_type, $postnord)){

                return "postnord";

            }


            return false;

        }
       


        public static function selector($address, $zipcode, $carrier_code, $country_code,$order_ref_id = 0){



            $r = self::pickup_points($address, $zipcode, $carrier_code, $country_code);
            
        

            include(__DIR__."/view/selector.php");

            

            

        }


        public static function get_price(){


            $client = self::load();


            $params = [
                'country_code' => 'DK',
                'carrier_code' => 'gls',
                'page' => 1
            ];
         

            return $client->getProducts($params);
         


        }



        public static function is_package_store($delivery_code){




            $gls = ["GLSDK_SD"];

    

            if(in_array($delivery_code,$gls)){

                return true;

            }

            return false;

        }
        

        public static function set_shipping_point($order_ref_id = 0,$shipping_point){


            if(!$order_ref_id){ $order_ref_id = Order::get_order_id(); }

            if(!$order_ref_id){ return false; }


            $client = self::load();
            
      
            $params = [
                'id' => $shipping_point,
                'carrier_code' => 'gls'
            ];
            

            $result = $client->getPickupPoints($params);


            if(empty($result)){ return false; }
            

            $res = $result["output"][0];
            
            $res["shipping_point"] = $res["id"];
            $res["company"] = $res["company_name"];

         
            Order::set_alternative_delivery_address($res);


        }


        public static function get_shipping_point($order_ref_id = 0){
            
            
            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }


            if(empty($order_ref_id)){ return false; }


            $result = Order::get_alternative_delivery_address($order_ref_id);

            $shipping_point = $result["shipping_point"];

            return $shipping_point;


        }


        public static function insert_alternative_delivery_address_by_shipping_point($shipping_point,$carrier){


            $client = self::load();


            $params = [
                'id' => $shipping_point,
                'carrier_code' => $carrier
            ];
            
        
            $r = $client->getPickupPoints($params)["output"];

            $r = Format::current($r);

            $name = "";


            $o = Order::get();

            if(self::is_package_store($o["delivery_type"])){ $name = "GLS Pakkeshop"; }
           
        
            
            $arr = ["firstname"     => $name,
                    "company"       => $r["company_name"],
                    "address"       => $r["address"],
                    "zipcode"       => $r["zipcode"],
                    "city"          => $r["city"],
                    "country_code"  => $r["country"],
                    "shipping_point" => $shipping_point
                    ];
            
               
            Order::set_alternative_delivery_address($arr);


        }


        public static function active(){

            
            $sql = "select id from shipping where active = 1";

            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }

        public static function create_shipping($arr = []){


            $delivery_type = "GLSDK_HD";
            $order_ref_id = "1";
            $order_reference = "1";


         


            $service_point = 95060;
            $weight = 1000;
            //extract($arr);


            $client = self::load();


            // Create shipment (code example in PHP)
            $params = [
                "test_mode" => true,
                "own_agreement" => true,
                "label_format" => "a4_pdf",
                "product_code" => "GLSDK_HD",
                "service_codes" => "EMAIL_NT,SMS_NT",
                "order_id" => "10001",
                "reference" => "Webshop 10001",
                "sender" => [
                    "name" => "Shipmondo ApS",
                    "address1" => "Strandvejen 6B",
                    "address2" => null,
                    "country_code" => "DK",
                    "zipcode" => "5240",
                    "city" => "Odense NØ",
                    "attention" => null,
                    "email" => "firma@email.dk",
                    "telephone" => "70400407",
                    "mobile" => "70400407"
                ],
                "receiver" => [
                    "name" => "Lene Jensen",
                    "address1" => "Vindegade 112",
                    "address2" => null,
                    "country_code" => "DK",
                    "zipcode" => "5000",
                    "city" => "Odense C",
                    "attention" => null,
                    "email" => "lene@email.dk",
                    "telephone" => "50607080",
                    "mobile" => "50607080",
                    "instruction" => null
                ],
                "parcels" => [
                    [
                    "weight" => 1000
                    ]
                ],
            ];


            

            

            try {
                
                
                $res = $client->createShipment($params);

                $res            = $res["output"]["labels"][0];

                $base64         = $res["base64"];
                $file_format    = $res["file_format"];

                return [];
                

            } catch (Exception $e) {

                return ["success"=>false,"msg"=>$e->getMessage()];

            }


        }



        public static function insert($arr = []){
            

            echo '
            <div id="shipping-destinatio-content" class="sektion">

                
                <div class="sektion-title">'.Sentence::translate("Select pickup point").'</div>
                
                <div class="content"></div>

            </div>
            ';


        }


    }

?>