<?php

	if(isset($_POST["key"]))
	if(isset($_POST["password"]))
	if(isset($_POST["repeat_password"])){
	
		
		
		$key = $_POST["key"];
		
		$password = $_POST["password"];
		
		$repeat_password = $_POST["repeat_password"];
        
        

		if(strlen($password) < 6){

            $arr = array("success"=>false,"msg"=>Sentence::translate("The password must be at least 6 characters long"));

            echo json_encode($arr);

            die();

        }


    
        if($password != $repeat_password){

            $arr = array("success"=>false,"msg"=>Sentence::translate("The specified passwords are not the same"));

            echo json_encode($arr);

            die();
        
        }
		
			
			
        $sql = "select * from website_forgot_password 
        where key_ref = '".$key."' and date >= ".strtotime("-30 minutes");
			
        $result = DB::select($sql);
            
			
        if(count($result) == 0){ 

            $arr = array("success"=>false,"msg"=>Sentence::translate("It is not possible to reset the password because the time has expired. Please try to reset again"));

            echo json_encode($arr);

            die(); 
            
        }
            

            
        foreach($result as $val) {
				
            $customer_ref_id = $val["customer_ref_id"];
				
            $link = $val["return_link"];
				
            $is_admin = $val["is_admin"];
					
        }

		
			
					
        if(isset($customer_ref_id)){
			
		
            $new_password = Password_manager::create($password);
				
				
				
            if($is_admin == 1){
                    
                    
                $sql = "update admin_users 
                set password = '".$new_password."' 
                where id = ".$customer_ref_id;
				
                DB::update($sql);		
												
                    
            } else {
                        
                    
                $sql = "update  customers 
                set password = '".$new_password."' 
                where id = ".$customer_ref_id;
				
                DB::update($sql);	
                
                    
            }			
				
					
            $sql = "delete from website_forgot_password 
            where key_ref = '".$key."' or date <= ".strtotime("-15 minutes");
				
            DB::update($sql);	
			
				
        }
        
        
        $sql = "delete from website_forgot_password where key_ref = '".$key."'";

        DB::delete($sql);


        $arr = array("success"=>true,"msg"=>Sentence::translate("Your password has now been changed, you are now relayed to the website."),"url"=>$link);

        echo json_encode($arr);

    
	}


?>