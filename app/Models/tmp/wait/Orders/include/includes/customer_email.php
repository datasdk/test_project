<?php



/*
    $f = Formular::get_by_id($formular_ref_id);


    $reciept_title   = trim($f["reciept_title"]);
    $reciept_message = trim($f["reciept_message"]);
*/


    if(empty($reciept_title)){

        $reciept_title = Sentence::translate("Thank you for your order!");

    }


    if(empty($reciept_message)){


        $reciept_message = 
        Sentence::translate("Your order confirmation is attached to this email along with the trading terms")
        ."".
        Sentence::translate("Please check if that the information on the order confirmation is correct. Contact us immediately if you find any errors.");


    }


    $reciept_message = trim($reciept_message);


    // information
  

    $information = Booking::get_information($order_ref_id);

    



    // message

    $message = "";

    $message .= "
    <div><h2>".$reciept_title."</h2></div>
    
    <div>".trim($reciept_message)."</div>

    <br>

    <div>".$order["firstname"]." ".$order["lastname"]."</div>

    <div>".$order["address"]." ".$order["floor"]." ".$order["door"]."</div>

    <div>".$order["zipcode"]." ".$order["city"]."</div>
    ";


    if($phone){ $message .= "<div>".Sentence::translate("Phone").": ".$phone."</div>"; }
    
    if($email){ $message .= "<div>".Sentence::translate("E-mail").": ".$email."</div>"; }


    $message .= "

    <br>

    <div style='padding:15px 0px 15px 0px'>".$information."</div>

    <div>".Sentence::translate("Best regards")."</div>

    <div><strong>".$com["company"]."</strong></div>

    <div><a href='https://".$_SERVER["HTTP_HOST"]."'>".$_SERVER["HTTP_HOST"]."</div>";



    $subject = $com["company"]." - ".Sentence::translate("Order confirmation");



    $output =
    Email::send($order["email"],$subject,$message,$attachments,true);


?>