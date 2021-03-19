<?php

    $token  = $_POST["token"];

    $m = DB::connect_by_token($token);



    $r = [];
 

    if($m){

  
        $r = Payment::onpay($_POST);
  

    }



    echo json_encode( $r );

    die();

?>   