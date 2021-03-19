<?php   

    
    namespace App\Models\Api\Api;
    

    Class Phone{


        public static function get(){


            $company = Company::get();

            $phone = current($company["phone"])["number"];
            return $phone;

            
        }

        
        public static function button(){


            $number = Phone::get(); 

            $label = Company::formatPhoneNumber($number);

            if(empty($number)){ return false; }

            
            return  "<a href='tel:".$number."' class='phone_button'><i class='fas fa-phone'></i> ".$label."</a>";

        }


        public static function validate($number){


            if (strlen($number) < 8) {
            
                return false;
            
            } else {
                
                return true;
            
            }

        }

    }

?>