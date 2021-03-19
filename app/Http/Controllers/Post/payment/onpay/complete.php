<?php

    $onpay_secret = "f81b4a36822093dff77b3b73c9655db1d877ba609ee1848dcf7be8423bcdad64dba6ff928e377c1420c6d27de7753ab65b5be2db28645914379ea5aefd97392a";



    if(isset($_GET["onpay_uuid"])){


        $onpay_uuid         = $_GET["onpay_uuid"];
        $onpay_number       = $_GET["onpay_number"];
        $onpay_reference    = $_GET["onpay_reference"];

        $onpay_currency     = $_GET["onpay_currency"];
        $onpay_method       = $_GET["onpay_method"];

        $onpay_cardtype     = $_GET["onpay_cardtype"];
        $onpay_hmac_sha1    = $_GET["onpay_hmac_sha1"];


        $accepturl          = $_GET["accepturl"];
        $declineurl         = $_GET["declineurl"];
        

        $customer_ref_id    = $_GET["customer_ref_id"];
        $order_ref_id       = $_GET["order_ref_id"];
        $subscription       = $_GET["subscription"];

        
    }


   
    

        
 
    $payment = new \OnPay\API\PaymentWindow();
    $payment->setSecret($onpay_secret);
           

    if($payment->validatePayment($_GET)) {
                  

        $r = Payment::get_payment();


        $domain = $r["domain"];
        $gateway_id = $r["merchant_id"];;
        $redirect_uri = Page::get_domain().'/payment/onpay/auth';
                
        $card_hint = '';
                

        
        if(!$gateway_id or !$domain){   return false;  }         
        

        
        try {
        

             
            $tokenStorage = new TokenStorage('.token.bin');
                           
            $onPayAPI = new \OnPay\OnPayAPI($tokenStorage, [
                'client_id' => $domain, // It is recommended to set it to the domain name the integration resides on
                'redirect_uri' => $redirect_uri,
                'gateway_id' => $gateway_id, // Should be set to the gateway id you are integrating with
            ]);
                    


            if($onPayAPI->isAuthorized()) {

                    
                $ccrg = $onpay_uuid;
        
                $card_type = $onpay_cardtype;
    
                $order_ref_id = Order::get_order_id();

                        
                echo "<h1>Payment was successfull</h1>";



                    if($order_ref_id){


                        $params = 
                        [
                            "is_paid"=>1,
                            "subscription"=>$subscription,
                            "ccrg"=>$ccrg
                        ];
                    

                        $r = Order::complete($params);


                        if(empty($r["success"])){
                            
                            echo "Order error: ".$r["msg"];
                                    
                        }
        
        
                    } else {


                       
                        if($subscription){


                            if($customer_ref_id){

                                $trans = $onPayAPI->subscription()->getSubscription($onpay_uuid);
                                                                                                    
                                $card_hint  = $trans->cardBin;

                            }
                            
                            else 
                            
                            {

                                echo "No customer id. Could not set CCRG";
        
                            }

                                    
                        } else {
                                        
                            $trans = $onPayAPI->transaction()->getTransaction($onpay_uuid);
                                        
                        }


                        Customer::set_ccrg($customer_ref_id,$ccrg,$card_hint,$card_type);


                    }
              

                        
                    if(strlen($accepturl) > 1){
                                

                        if (strpos($accepturl, '?') !== false) { $accepturl .= "&"; } 
                        else { $accepturl .= "?"; }


                        $accepturl .= "ccrg=".$ccrg."&subscription=".$subscription."&card_type=".$card_type;
                        
                        header("location: ".$accepturl);
                            

                        die();


                    } else {

                        sa($_GET);

                    }
                        


                } else {
        
                    echo "<h1>Payment is not authorized</h1>";

                    echo "<a href='".$declineurl."'>Return to website</a>";       
                      
                    echo "<br><a href='".$redirect_uri."'>auth</a>"; 
           

                }


            } catch (\Exception $e) {
                    
                echo "Error: ";
                echo $e->getMessage();
                                        
            }



    } else {

        echo "There was an error with the payment";

    }
         

?>