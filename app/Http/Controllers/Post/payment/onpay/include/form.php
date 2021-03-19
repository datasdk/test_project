<?php
    
    
  
    $gateway_id = "3020031391945523";
    $secret = "f81b4a36822093dff77b3b73c9655db1d877ba609ee1848dcf7be8423bcdad64dba6ff928e377c1420c6d27de7753ab65b5be2db28645914379ea5aefd97392a";
                    

    $testmode = 0;
    $currency = "208";
    $customer_name = 'Kasper Kristiansen';
    $customer_email = 'datas.webbureau@gmail.com';
    $customer_phone = '93307121';
    $currency = "DKK";
    $lang = "da";
    $secure = true;
    $lang = "da";
    $reference = "trans_".time();
    $trans_type = 'payment';
    $amount = 100;






    if(!$order_ref_id){ $order_ref_id = Order::get_order_id(); }

    $order = Order::get($order_ref_id);


  
    if($testmode){


        $reference = "test_".time();

        $amount = 100;


    } else if($order){


        $reference = $order["reference_number"];

        $amount = Order::load_prices($order_ref_id)["subtotal"];

        $amount *= 100;


    }



    if($subscription){

        $trans_type = 'subscription';
        $amount = 1;

    } else if(!$order){ 
        
        die("Payment failed - No order defined"); 
    
    } else if($amount < 100){ 
        
        die("Payment failed - amount invalid"); 
    
    }





    $paymentWindow = new \OnPay\API\PaymentWindow();
    $paymentWindow->setGatewayId($gateway_id);
    $paymentWindow->setSecret($secret);
    $paymentWindow->setCurrency($currency);


    $paymentWindow->setAmount($amount);
            // Reference must be unique (eg. invoice number)
    $paymentWindow->setReference($reference);
  
    $paymentWindow->setAcceptUrl($accepturl);
    $paymentWindow->setDeclineUrl($declineurl);
    $paymentWindow->setType($trans_type);
    $paymentWindow->setDesign("window1");
    // Force 3D secure
    $paymentWindow->setSecureEnabled($secure);
    // Set payment method to be card
    $paymentWindow->setMethod(\OnPay\API\PaymentWindow::METHOD_CARD);
    // Enable testmode
    $paymentWindow->setTestMode($testmode);
    $paymentWindow->setLanguage($lang);
            
    // Add additional info
    $paymentInfo = new \OnPay\API\PaymentWindow\PaymentInfo();
    $paymentInfo->setName('Kasper Kristiansen');
    $paymentInfo->setEmail('datas.webbureau@gmail.com');
    // And so on, a lot more fields should be set if data available for it
         
  
    $paymentWindow->setInfo($paymentInfo);
            
?>
            
            <?php if($paymentWindow->isValid()) { ?>
            <form id="onpay_submit" method="post" action="<?php echo $paymentWindow->getActionUrl(); ?>" accept-charset="UTF-8">
                <?php
                    foreach ($paymentWindow->getFormFields() as $key => $value) { ?>
                        <input type="hidden" name="<?php echo $key;?>" value="<?php echo $value;?>">
                <?php } ?>
              
            </form>
            
            <?php } else { ?>
                <h1>Payment window is not configured correct</h1>
            <?php } ?>



<script>
document.getElementById("onpay_submit").submit();
</script>


