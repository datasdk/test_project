<?php


    $cardno     = strval($_POST["cardno"]);
    $expyear    = strval($_POST["expyear"]);
    $expmonth   = strval($_POST["expmonth"]);
    $cvc        = strval($_POST["cvc"]);


    $arr = [
        "cardno"=>$cardno,
        "expyear"=>$expyear,
        "expmonth"=>$expmonth,
        "cvc"=>$cvc
    ];

    $result = Yourpay::request($arr);


    $lang = Languages::lang_url();

    $url = $lang . "/checkout/complete";



    if(!empty($result)){


        if($result["success"]){


            $order_ref_id = Order::get_order_id();
    

            $sql = "
            update orders set 
            is_paid = 1, 
            payment_date = '".time()."' 
            where id = '".$order_ref_id."'";
    
            DB::update($sql);
    
       
    
        } 
        
        
        $result["url"] = $url;


        echo json_encode($result);

        
    } else {


        $msg = Sentence::translate("Unfortunately, the payment system is out of order, please choose another payment method");


        echo json_encode(array("success"=>false,"msg"=>$msg,"error"=>"no_response","url"=> $url));


    }
    

?>