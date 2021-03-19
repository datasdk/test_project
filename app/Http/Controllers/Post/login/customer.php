<?php
    
    

    if(isset($_POST["email"]) and isset($_POST["password"])){

        
        $group_ref_id = 0;

        $email = $_POST["email"];

        $password = $_POST["password"];


        if(isset($_POST["group_ref_id"])){

            $group_ref_id = $_POST["group_ref_id"];

        }


        
        if(Login::log_in($email,$password,$group_ref_id)){

            echo json_encode( ["success" => true] );

            die();

        } 


        echo json_encode( ["success"=>false, "title"=> Sentence::translate("Invalid login"), "msg" => Sentence::translate("E-mail or password is invalid")] );

      

    } else {

        echo json_encode( ["success"=>false, "title"=> Sentence::translate("Invalid login"), "msg" => Sentence::translate("Please enter an E-mail and password")] );

    }
    

?>