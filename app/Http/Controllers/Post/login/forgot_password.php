<?php


        $email = DB::escape($_POST["email"]);

		// fjern alle forældede tokens
		
		$sql = "delete from website_forgot_password where ".strtotime("-15 minutes")." > date";;
		DB::delete($sql);

		

		// check om der allerede findes en token
		
		$sql = "select * from website_forgot_password where email = '".$email."'";
		
		if(DB::numrows($sql) > 0){
            
            $arr = array("success"=>false,"title"=>Sentence::translate("E-mail was not sent"),"msg"=>Sentence::translate("E-mail is already sent to your E-mail account"));

            echo json_encode($arr);
            
			die();	
			
		}
		
		
		
		// CHECK OM KUNDEN EMAIL EKSISTERER
				
		$sql = "select * from customers where email = '".$email."' limit 1";
        
        $result = DB::select($sql);
        

		if(count($result) == 0){ 
			
			$arr = array("success"=>false,"title"=>Sentence::translate("E-mail was not sent"),"msg"=>Sentence::translate("The specified Email does not exist in the system"));

            echo json_encode($arr);
			
			die();
			
		}


		else
		
		
		foreach($result as $val){
			
			
			$firstname = $val["firstname"];
			$lastname = $val["lastname"];
			$kunde_ref_id = $val["id"];
			$server = $_SERVER['SERVER_NAME'];
			$dato = date("d/m Y - H:i:s");
			$host = $_SERVER['HTTP_HOST'];
            $prodocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https' : 'http';

			$return_url = $prodocol."://".$_SERVER['HTTP_HOST']."/checkout/info";
            
            
            $subject = Sentence::translate("Reset password for your user account at")." ".$host;
            
			$key = sha1(uniqid());

			$lang = Language::get_code_by_language_id();
			
			$link = $prodocol ."://". $host . "/" . $lang . "/reset_password/".$key;
			
			
			
			
			// SEND MAIL TIL KUNDEN
			
			$message = Sentence::translate("Hello");


			$message .= "<br><br>";

			$message .= Sentence::translate("You have requested a reset of your password")." ";
			

			$message .= $host;
			
			$message .= "<br>";

			$message .= Sentence::translate("Click the link below to reset your password.");
			
			$message .= "<br><br>";


			$message .= "<a href='".$link."'>";
			
			$message .= Sentence::translate("CLICK HERE TO RESET THE PASSWORD");
			
			$message .= "</a>";
			
			$message .= "<br><br>";
			

			$message .= Sentence::translate("Yours sincerely");
			
			$message .= "<br>";

			$message .= $server."<br />";

			$message .= Sentence::translate("Date").": ";
			
			$message .= $dato;
			
	
			
			Email::send($email,$subject,$message,false,true);
			
			
			// INDSÆT TOKEN I DATABASEN
			
			
			$arr = array("customer_ref_id"=>$kunde_ref_id,
                         "is_admin"=>0,
                         "key_ref"=>$key,
						 "email"=>$email,
						 "date"=>time(),
						 "return_link"=>$return_url
						 );

			DB::insert("website_forgot_password",$arr)	;			
			
			
            $arr = array("success"=>true,"title"=>Sentence::translate("E-mail has now been sent"),"msg"=>Sentence::translate("We have now sent you an E-mail in which you can reset your password"));

            echo json_encode($arr);
			
			
		}
	



?>