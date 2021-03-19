
<?php
    
    namespace App\Models\Api\Api;


    class Newsletter{
    

	    public static function get($show_title = true,$default_email = "",$unsubscript = false){


            include(__DIR__."/includes/newsletter_field.php");


        }



        public static function get_customer_from_email($email){


            $sql = "
            select * from customers
            where email = '".$email."' limit 1";


            $result = current(DB::select($sql));


            if(!empty($result)){


                return $result["id"];

            }

            return false;

        }

        
        public static function is_subscribed($email){

            
            $sql = "
            select * from customers_newsletters 
            where 
            email = '".$email."'
            and
            accept_newsletters = 1 
            limit 1";
                
            
            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }


        public static function subscribe($email){
                

            // customers_newsletters

            $sql = "select * from customers_newsletters where email = '".$email."' limit 1";
                    

            if(!DB::numrows($sql)){
                

                $key_id = sha1(uniqid());


                $arr = array("key_id"=>$key_id,
                             "email"=>$email,
                             "accept_newsletters"=>1,
                             "newsletter_date"=>time()
                            );

                DB::insert("customers_newsletters",$arr);
                

            } else {


                $sql = "
                update customers_newsletters 
                set accept_newsletters = 1 
                where email = '".$email."'";

                DB::update($sql);


            }


        }



        public static function unsubscribe($email){

            $sql = "update customers_newsletters set 
                    accept_newsletters = 0 
                    where email = '".$email."'";

                    DB::update($sql);

        }


    }
    


?>