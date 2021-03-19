<?php

    namespace App\Models\Api\Api;

    class Payment {


        public static $domain;
        public static $gateway_id;


        public static function insert(){

            include(__DIR__."/includes/payment_windows.php");
            
        }

       
        public static function get($name = false){


            $sql = "select * from settings_payment  where ";
            
            if($name){

                $sql .= " name = '".$name."' and ";

            }
            
            $sql .= "  active = 1";


            $result = DB::select($sql);

            return $result;
        
        }



        public static function translate($payment_type){

            if($payment_type == "stripe"){  return Sentence::translate("Online card payment"); }
            if($payment_type == "mobilepay"){   return "Mobilepay"; }
            if($payment_type == "bank"){        return Sentence::translate("Bank transfer"); }
            if($payment_type == "arrive"){      return Sentence::translate("Pay on arrival"); }

        }



        public static function icons($options = []){

            
            $align = "right";

            $baseurl = "/assets/images/checkout/";

            $card = ["visa.png","mastercard.png","maestro.png"];
    

            extract($options);


            ob_start();


                echo "<div class='card_icons ".$align."'>";


                    foreach($card as $url){

                        echo "<img src='".$baseurl.$url."'>";

                    }
    

                echo "</div>";



                $content = ob_get_contents();


            ob_end_clean();



            return $content;   
            


        }


        private static function error(){

            $title  = Sentence::translate("Order does not exist");

            $msg    = Sentence::translate("No active orders found");

            return array("success"=>0,"title"=>$title,"msg"=>$msg);

        }
        

        public static function is_test($payment_type = 'stripe'){


            $sql = "select id from settings_payment where live = 0 and name = '".$payment_type."'";

            
      
            if(DB::numrows($sql)){

                return true;

            }


            return false;


        }

        
  /*
        public static function onpay_subscribe($customer_ref_id = 0,$accepturl = 0,$declineurl = 0) {


          
            
 

        }




      
        public static function onpay($option = []) {
        
            

            $order_ref_id = 0;
            $accepturl = "/";
            $declineurl = "/";
            $ccrg = 0;
            $subscription = 0;

            extract($option);

            

            if($order_ref_id != "test"){


                
                if(!$order_ref_id){

                    $order_ref_id = Order::get_order_id();

                }


                if(!$order_ref_id){

                    return ["success"=>false,"error"=>"Order dosent exists"];

                }


                $customer_ref_id = Order::get_customer_by_order_id($order_ref_id);


                if(!$ccrg){

                    $ccrg = Customer::get_ccrg($customer_ref_id);

                }


            }
           
        

        
            $domain = Page::get_domain();


            if($ccrg and $subscription){

     
                $params = ["order_ref_id"=>$order_ref_id,
                           "subscription" => $subscription, 
                           "ccrg" => $ccrg
                        ];

             
                $r = Order::complete($params);
               
           
        
                if(!empty($r["success"])){


                   return ["success"=>true,"url"=>$accepturl];


                } else {

                    return $r;

                }
             
           

            } else {
     

                $url = $domain."/payment/onpay/open?";

                $url .= "accepturl=".urlencode($accepturl)."&";
                $url .= "declineurl=".urlencode($declineurl)."&";
                $url .= "order_ref_id=".$order_ref_id."&";
                $url .= "customer_ref_id=".$customer_ref_id."&";
                $url .= "subscription=".$subscription;
                
           
                return ["success"=>true,"url"=>$url];

            }


    
        }



        public static function withdraw($order_ref_id = 0,$ccrg = 0){



            if($order_ref_id != "test"){


                if(!$order_ref_id){

                    $order_ref_id = Order::get_order_id();
    
                }
    
                if(!$order_ref_id){
    
                    return ["success"=>false,"msg"=>"Order dosent exists"];
    
                }


                $o = Order::get($order_ref_id);

                $customer_ref_id = Customer::get_customer_id_by_email($o["email"]);
    
                if(!$ccrg){
    
                    $ccrg = Customer::get_ccrg($customer_ref_id);
    
                }


                $reference_number = $o["reference_number"];
            
                $sum = (Order::load_prices($order_ref_id)["sum"] * 100);


            }

            
                        

           // $domain = self::$domain;
           // $gateway_id = self::$gateway_id;


            $r = Payment::get("onpay");


            $domain = $r["domain"];
            $gateway_id = $r["merchant_id"];;
            $redirect_uri = Page::get_domain().'/payment/onpay/auth';


            if(!$gateway_id or !$domain){   return false;  }         


            try {



                $tokenStorage = new TokenStorage('.token.bin');
             
            
                $onPayAPI = new \OnPay\OnPayAPI($tokenStorage, [
                    'client_id' => $domain, // It is recommended to set it to the domain name the integration resides on
                    'redirect_uri' => $redirect_uri,
                    'gateway_id' => $gateway_id, // Should be set to the gateway id you are integrating with
                ]);
            
       
          
                if($onPayAPI->isAuthorized()) {
                 
                    
                    if(empty($ccrg)){

                        return ["success"=>false,"msg"=>"ccrg is empty"];

                    }

                    
                    $subscriber = $onPayAPI->subscription()->getSubscription($ccrg);

                         
                   
                    if($subscriber){

                       // $sum = 300;

                         $r = $onPayAPI->subscription()->createTransactionFromSubscription($ccrg, $sum , $reference_number);
                         
                       
                         if(empty($r)){
     
                             return ["success"=>false,"msg"=>"ccrg dosent exists"];
     
                         } 
                      
                 
              
                         
                         $params = ["is_paid"=>1];
                     
                         $order = Order::complete($params);
  
     
                         return $order;

                         

                    } else {

                        return ["success"=>false,"msg"=>"Creditcard is not subscribed"];

                    }

                 

          
                }             

            
            } catch (Exception $e) {
                

                $s = ["success"=>false,
                      "msg"=>$e->getMessage()
                    ];

                return $s;
            

            }
         

        }
        */
   


    }

?>