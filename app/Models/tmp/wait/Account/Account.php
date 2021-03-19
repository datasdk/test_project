<?php

    namespace App\Models\Account\Account;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
  
    class Account extends Model{

        use HasFactory;
        
        public static $page;
        


        public static function insert($options = []){


            $has_header = true;
            $has_profile = true;
            $has_order = true;
            $has_newsletter = true;
            $has_change_password = true;
            $has_logoff = true;
            $has_creditcard = true;
            $has_header = true;



            extract($options);



            $page = self::$page;


            $customer_ref_id = Customer::id();

            $newsletters = Settings::get("newsletters");

            
            $options["newsletters"] = $newsletters;
            $options["customer_ref_id"] = $customer_ref_id;
            $options["has_header"] = $has_header;




            if(empty($customer_ref_id)){


                return ["success"=>false,"msg"=>"Please log in to use the account"];

            }


           

            include(__DIR__."/includes/header.php");
       
            
 
     
            if($page == "orders" and $has_order){


                self::orders($options);
                

            } 

            else

            if($page == "preview" and $has_order){


                self::preview($options);
           

            } 

            else

            if($page == "payment" and $has_creditcard){


                self::payment($options);
                
             
            } 
                
            else

            if($page == "newsletter" and $newsletters and $has_newsletter){


                self::newsletter($options);
                    

            } 

            else

            if($page == "password" and $has_change_password){
                

                self::password($options);
            

            }

            else 

            if($has_profile){


                self::my_profile($options);
           
            }



            include(__DIR__."/includes/footer.php");

            

        }



        public static function my_profile($options = []){


            $has_header = true;


            extract($options);


            include(__DIR__."/includes/my_account/my_account.php");


        }


        public static function password($options = []){


            $has_header = true;


            extract($options);


            include(__DIR__."/includes/password/password.php");


        }


        public static function newsletter($options = []){


            $has_header = true;


            extract($options);


            include(__DIR__."/includes/newsletter/newsletter.php");


        }


        public static function payment($options = []){


            $has_header = true;


            extract($options);


            include(__DIR__."/includes/payment/payment.php");


        }


        public static function preview($options = []){
            

            $has_header = true;


            extract($options);


            include(__DIR__."/includes/orders/preview.php");
         

        }
    

        public static function orders($options = []){


            $has_header = true;


            extract($options);


            include(__DIR__."/includes/orders/overview.php");


        }
    

    }

?>