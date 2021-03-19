<?php


    include(__DIR__."/includes/header.php");

    include(__DIR__."/includes/create_email_content.php");


 

    if(!empty($info["email"])){



        $company = Company::get();


        // COSTUMER EMAIL



        
        if(isset($info["email"]))
        if(!Email::validate($info["email"])){

            
            echo json_encode(array("success"=>false,"title"=>Sentence::translate("Email is invalid"),"msg"=>Sentence::translate("The specified Email address is not correct")));
            
            exit();

        }
      
        /*

        if(strlen($message) < 10){

            echo json_encode(array("success"=>false,"title"=>"Beskeden er for kort","msg"=>"Din besked skal mindst fylde 10 tegn"));
            
            exit();

        }

        */
    

        if(isset($info["phone"]))
        if(!Phone::validate($info["phone"])){

            echo json_encode(array("success"=>false,"title"=>Sentence::translate("Phone number is invalid"),"msg"=>Sentence::translate("The specified phone number is not correct")));
            
            exit();

        }


      
        $title = Sentence::get($form["reciept_title"]);

        $message = Sentence::get($form["reciept_message"]);

        $email = $info["email"];



        if(empty($title)){

            $title = Sentence::translate("Thank you for your inquiry");

        }

        if(empty($message)){
            
            $message = Sentence::translate("We have now registered your inquiry and we will endeavor to respond soon.");

        }

        $subject = $company["company"]." - ".$title." - ".date("d/m Y H:i");
        

        
        $msg = create_email_content($title,$message,$form,$specifications,$info);


        $m = Email::send($email, $subject, $msg, false, true);


        if(!$m["success"]){

            echo json_encode(["success"=>false,"title"=>"Sending error (customer)","msg"=>$m["msg"]]);

            die();

        }   




        // ADMIN EMAIL

        if(isset($_POST["receive"])){
            

            $email_id = $_POST["receive"];

            $sql = "select * from company_email where id = '".$email_id."'";

            $admin_email = current(DB::select($sql))["email"];

            

        } else {


             $support_emails = Company::get_all_emails("support");
           
             if(!empty($support_emails)){

                $admin_email = current($support_emails)["email"];

             }
            
            

        }
        
  
        // IF NO EMAILS IN THE SYSTEM AT ALL
        
        if(empty($admin_email)){

            echo json_encode(array("success"=>false,"title"=>Sentence::translate("Technical error"),"msg"=>Sentence::translate("Support is not available, please try later")));
            
            exit();

        }
        


        $title = Sentence::translate("You have received a new message");

        $message = Sentence::translate("You have received a new message from")." ";
        
        $message .= $_SERVER["HTTP_HOST"];
        

        $subject = $company["company"]." - ".$info["first_name"];
        
        if(isset($info["last_name"])){ $subject .= " ".$info["last_name"];} 
        
        $subject .= " - ".date("d/m Y H:i");
        


        $msg = create_email_content($title,$message,$form,$specifications,$info);

       
        // virker åbenbart ikke hos simpy???????? så vi sætter den til false
        $med_skabelon = false;

        $m = Email::send($admin_email, $subject, $msg, false, $med_skabelon, $email);



        if(!$m["success"]){

            echo json_encode(["success"=>false,"title"=>"Sending error (admin)","msg"=>$m["msg"]]);

            die();

        }   



        $title = Sentence::translate("Thank you for your inquiry");
        $msg   = Sentence::translate("We have received your inquiry and will return as soon as possible. We sent a confirmation to your email.");


        $arr = array("success"=>true,"title"=>$title,"msg"=>$msg,"admin_email"=>$admin_email,"email"=>$email);

    
        echo json_encode($arr);


    }
    
    


?>