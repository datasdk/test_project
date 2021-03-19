<?php




    // REUIRED FIELDS
    
    $email          = $_POST["email"];
    
    $password       = $_POST["password"];

    
  


    // EXTRA FIELDS


    $company = "";
    $cvr = "";
    $ean = "";
    $firstname = "";
    $lastname =  "";
    $address = "";
    $housenumber = "";
    $floor = "";
    $city =  "";
    $zipcode = "";
    $phone =  "";
    $comment = "";
    


    if(isset($_POST["token"])){             $token = $_POST["token"]; }
    
    if(isset($_POST["organization"])){      $organization  = $_POST["organization"]; }

    if(isset($_POST["cvr"])){               $cvr  = $_POST["cvr"]; }

    if(isset($_POST["ean"])){               $ean  = $_POST["ean"]; }

    if(isset($_POST["firstname"])){         $firstname = $_POST["firstname"]; }
    
    if(isset($_POST["lastname"])){          $lastname = $_POST["lastname"]; }
    
    if(isset($_POST["address"])){           $address = $_POST["address"]; }

    if(isset($_POST["housenumber"])){       $housenumber = $_POST["housenumber"]; }

    if(isset($_POST["floor"])){             $floor = $_POST["floor"]; }

    if(isset($_POST["city"])){              $city = $_POST["city"]; }

    if(isset($_POST["zipcode"])){           $zipcode = $_POST["zipcode"]; }

    if(isset($_POST["phone"])){             $phone = $_POST["phone"]; }

    if(isset($_POST["comment"])){           $comment = $_POST["comment"]; }
    
    
    

    if(!isset($_POST["terms_of_trade"])){


        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Accept the terms"),"msg"=>Sentence::translate("Please accept the terms and privacy policy")));

        exit();

    }


    if(!Email::validate($email)){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("The E-mail is invalid"),"msg"=>Sentence::translate("Please enter a valid E-mail")));

        exit();

    }


    if(Customer::exists_by_email($email)){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("The E-mail already exists"),"msg"=>Sentence::translate("You can not sign up with this E-mail because it is own by another user")));

        exit();

    }


    if(strlen($password) < 6){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("The password is to short"),"msg"=>Sentence::translate("The password is to short. Please enter 6 characters")));

        exit();

    }





    $customer_ref_id = 
    Customer::add($company,$cvr,$ean,$firstname,$lastname,$address,$housenumber,$floor,$zipcode,$city,$phone,$email,$password,$comment);
    


    if($customer_ref_id){
        

        Customer::send_welcome_email($customer_ref_id,$password,true);

        echo json_encode(array("success"=>true,"title"=>Sentence::translate("Your are now signed up"),"msg"=>Sentence::translate("You can now use your user-account. You will soon recieve a confirmation by E-mail")));

        exit();


    } else {


        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Technical error"),"msg"=>"You were not signed up as a customer, please try again later"));

        exit();

    }
    

   

?>