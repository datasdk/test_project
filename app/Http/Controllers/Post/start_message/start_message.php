<?php


    $start_msg = Settings::get("start_msg");

    

    if($start_msg)
    if(!isset($_SESSION["startup_message"])){


        $_SESSION["startup_message"] = true;
        
        $start_msg_text = Settings::get("start_msg_text");

        echo json_encode(["shown"=>false,"title"=> "Information","msg"=>$start_msg_text]);

        exit();

    }

    
    echo json_encode(["shown"=>true]);

    exit();




?>
