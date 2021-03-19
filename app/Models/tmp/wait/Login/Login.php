<?php

    namespace App\Models\Api\Api;

    
    class Login{


        public static function insert($arr = []){


            ob_start();
            

                $accept_url = "";

                $object_ref_id = 0;

                $hide_on_login = false;

                $group = 0;

                $message_on_login = false;
                
                $title = "";


                extract($arr);

                

                $group_ref_id = Customer::get_group_by_name($group);
                



                if($message_on_login and self::is_logged_in()){

                    echo "<div class='alert alert-info'>Du er nu logged ind</div>";

                }

                
                // VIGTIGT! Denne skal ikke være aktiv
                if($hide_on_login and self::is_logged_in()){ return false; }



                if(!$accept_url)
                $accept_url = Frontend::get_parameter($object_ref_id,"accept_url");

                
                include(__DIR__."/includes/login_module.php");



            $content = ob_get_contents();

            ob_end_clean();


            return $content;


        }




        public static function floot_control(){


            $customer_login_try = Cookie::get("customer_login_try");


            if(empty($customer_login_try)){

                $customer_login_try = 0;
    
            }


            $customer_login_try ++;


            if($customer_login_try > 10){

                Cookie::set("customer_login_blocked_time", strtotime("+5 minuttes"));

                return true;
            }
            
            
            return false;

        }


        public static function check($type = "customer"){


            $mysqli = DB::mysqli();
    
            
            $login_id           = Cookie::get("customer_login_id");	
            $login_email        = Cookie::get("customer_login_email");	
            $login_password     = Cookie::get("customer_login_password");	
                    
    
    
            if(!empty($login_id))
            if(!empty($login_email))
            if(!empty($login_password))
            {
            
                
                if($type == "customer"){

                    $db = "customers";

                } else {

                    $db = "admin_users";

                }


                // admin konti
                            
                $sql = "
                select * from ".$db." 
                where id = '".$login_id."' and email = '".$login_email."'";
                
                
                $result = mysqli_query($mysqli,$sql);
                
                
                            
                if(mysqli_num_rows($result) == 0){ 
                    
                 
                    return false; 
                    
                    
                } 
                        
                else
                        
                {
                    
            
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                
                        
                        if($login_password == $row["password"]){
                                        
                            
                            return $login_id;
                                                
                            
                        } else {
                                
                                  
                            return false;
                            
                                
                        }
                        
                        
                    }
                            
                            
                
                }
                
            
            }
            
    
            return false;
    
    
        }


        
        public static function is_logged_in($group_name = 0){


            return Customer::is_logged_in($group_name);


        }
        
        

        public static function log_in($email,$password,$group_ref_id = 0){


                
            $sql = "
            select * from customers 
            where 
            email = '".$email."'";


            if($group_ref_id){

                $sql .= " and customer_groupe_ref_id = '".$group_ref_id."'";

            }

        

            $result = DB::select($sql);
            

            
            if($result)
            foreach($result as $val){
               

                $crypt_password = $val["password"];
               
              
                if(Password_manager::check($password,$crypt_password)){
                

                    $id = $val["id"];

                 

                    Cookie::set("customer_login_id",$id);	
                    Cookie::set("customer_login_email",$email);	
                    Cookie::set("customer_login_password",$crypt_password);
                    Cookie::set("customer_login_group",$group_ref_id);

              
                    
                    $customers = Customer::get($id);

                    $order_ref_id = Order::get_order_id();



                    if($order_ref_id)
                    if($customers){


                        $sql = "
                        update orders set 
                        company = '".$customers["company"]."',
                        cvr = '".$customers["cvr"]."',
                        firstname = '".$customers["firstname"]."',
                        lastname = '".$customers["lastname"]."',
                        address = '".$customers["address"]."',
                        housenumber = '".$customers["housenumber"]."',
                        floor = '".$customers["floor"]."',
                        zipcode = '".$customers["zipcode"]."',
                        city = '".$customers["city"]."',
                        email = '".$customers["email"]."',
                        phone = '".$customers["phone"]."'
                        where id = '".$order_ref_id."'
                        ";

                       
                        DB::update($sql);


                    }
       
                    
                    //if(!$customers){ return false; }


                    return $id;

                }


            }


            return false;


        }



        public static function log_off(){

            Order::disconnect();

            return Cookie::remove("customer_login_id");


        }


        public static function button($arr = []){


            $show_on = true;
            $show_off = true;
            $accept_url = "";

            $icon = "fas fa-user-alt";


            extract($arr);



            ob_start();

                
        
                $customer_ref_id = Login::check();


                if($customer_ref_id){
                    
                    if(!$show_on){ return false; }

                    $url = "javascript:customer_log_off()";

                    $label = "Log af";
                    
                } else {

                    if(!$show_off){ return false; }

                    $url = "javascript:open_login('".$accept_url."')";

                    $label = "Log in";

                }
                
     

                echo '
                <a href="'.$url.'" class="login-button">
                <i class="'.$icon.'"></i> '.$label.'
                </a>
                ';


                $content = ob_get_contents();

            
            ob_end_clean();


            return $content;


        }

        
    }

?>