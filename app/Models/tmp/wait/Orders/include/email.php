<?php


    $com = Company::get();


    $reciept = Reciept::create($order_ref_id);

    $terms = Terms::get();


    $file_reciept = false;
    $file_terms = false;

    
    if($attach_appendix){

        $file_reciept =  Pdf::invoice($order_ref_id);
    
    }

    if($attach_terms)
    if(!empty($terms)){

        $file_terms   = Pdf::create($terms,"assets/pdf/".uniqid().".pdf");

    }
    

    
    $attachments = false;


    if($attach_files){

        $attachments = [Sentence::translate("Appendix") => $file_reciept , Sentence::translate("Terms")=>$file_terms ];

    }
   


    $phones = array();

    foreach($com["phone"] as $val){

        $phones[] = ucfirst($val["name"]).": ".$val["number"]."";

    }

    
    $emails = array();



    $transaction_mails = Company::get_all_emails("transactions",true);

    $support_mails = Company::get_all_emails("support");



    foreach($support_mails as $val){

        $emails[] = ucfirst($val["name"]).": ".$val["email"]."";

    }


    // if empty, send to first
    if(empty($transaction_mails)){

        $transaction_mails[] = current($com["email"])["email"];

    } else {



    }



    Cookie::set("pdf",[$file_reciept,$file_terms]);

    
    
    // SEND EMAILS

    $firstname = false;
    $lastname = false;
    $address = false;
    $zipcode = false;
    $floor = false;
    $door = false;
    $cvr = false;
    $phone = false;
    $email = false;

    
    if(isset($order["firstname"])){     $firstname  = $order["firstname"]; }
    if(isset($order["lastname"])){      $lastname   = $order["lastname"]; }
    if(isset($order["address"])){       $address    = $order["address"]; }
    if(isset($order["floor"])){         $floor      = $order["floor"]; }
    if(isset($order["door"])){          $door       = $order["door"]; }
    if(isset($order["zipcode"])){       $zipcode    = $order["zipcode"]; }
    if(isset($order["cvr"])){           $cvr        = $order["cvr"]; }
    if(isset($order["phone"])){         $phone      = $order["phone"]; }
    if(isset($order["email"])){         $email      = $order["email"]; }

   



    /**
     * COSTUMER EMAIL 
    */

    if($send_customer_mail){

        include(__DIR__."/includes/customer_email.php");
    
    }
 

    /**
     * TO ADMIN 
    */

    if($send_admin_mail){

        include(__DIR__."/includes/admin_email.php");

    }

    
?>