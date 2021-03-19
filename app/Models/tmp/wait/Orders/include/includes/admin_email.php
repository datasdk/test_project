<?php


    $dl = Language::get_default_language();
    

    $message = "
    <h2>".Sentence::translate("New order from",$dl)." ".$com["company"]."</h2>
    
    ".Sentence::translate("You got a new order from",$dl)." ".$_SERVER["HTTP_HOST"]."
    
    <br>
    
    ".Sentence::translate("The order is attached as PDF via this mail",$dl)."

    <br><br>

    <div>".$company."</div>
    <div>".$firstname." ".$lastname."</div>
    <div>".$address." ".$floor." ".$door."</div>
    <div>".$zipcode." ".$city."</div>

    ";


    if($cvr){

       $message .= "<div>Cvr: ".$cvr."</div>"; 

    }
    



    if($phone or $email){


        if($phone){ $message .= "<div>".Sentence::translate("Phone",$dl).": ".$phone."</div>"; }
        if($email){ $message .= "<div>".Sentence::translate("E-mail",$dl).": ".$email."</div>"; }


    }
    

    $message .= "<br>";


    $information = Booking::get_information($order_ref_id,$dl);
    
    $message .= "<div>".$information."</div>";
    



    $subject =  Sentence::translate("New order from",$dl)." ";

    $subject .= $com["company"]." - ".date("d/m Y H:i");



    if(!empty($transaction_mails)){

        $result = Email::send($transaction_mails,$subject,$message,$attachments,true);

    }


?>