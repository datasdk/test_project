<?php
/*
    try {
    

        $r = Payment::get_payment();


        $domain = $r["domain"];
        $gateway_id = $r["merchant_id"];;
        $redirect_uri = Page::get_domain().'/payment/onpay/auth';
            
        $revalidate = false;


        $tokenStorage = new TokenStorage('.token.bin');
            
    

        $onPayAPI = new \OnPay\OnPayAPI($tokenStorage, [
            'client_id' => $domain, // It is recommended to set it to the domain name the integration resides on
            'redirect_uri' => $redirect_uri,
            'gateway_id' => $gateway_id, // Should be set to the gateway id you are integrating with
        ]);
            
    
    


        
        // Special handling if we are about to auth against the API
        if (isset($_GET['auth'])) {
            
            $authUrl = $onPayAPI->authorize();

            header('Location: ' . $authUrl);

            exit;

        } 



        
        if (isset($_GET['code'])) {


            $onPayAPI->finishAuthorize($_GET['code']);
            echo 'Authorized :tada:' . PHP_EOL;

        }
        

        // Check if we need to authenticate
        if (!$onPayAPI->isAuthorized()) {

            $revalidate = true;

        }
        

        // Execute API method
        $result = $onPayAPI->ping();
        $pong = false;


        if(isset($result["data"]["pong"])){

            $pong = $result["data"]["pong"];    

        }



        if($pong == $gateway_id){


            $domain = Domain::get("admin");

            if($domain){

                $url = $domain."/settings/payments";

            } else {

                $url = "/";

            }


            header("location: ".$url);

            die();


        } else {

            echo "<h1>ERROR</h1>";

            sa($onPayAPI);

        }

        
    

 
    } catch (\Exception $e) {
                
        echo "<h1>Error:</h1>";

        echo $e->getMessage();

        echo "<hr>";

        $revalidate = true;
                                    
    }


    if($revalidate){

        echo 'Not authorized for the API! ' . PHP_EOL;

        header("location: ?auth");
       // echo '<a href=?auth>Click here to initiate authorization</a>' . PHP_EOL;

        exit;

    }
    
*/
 ?>
 
