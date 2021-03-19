<?php

    namespace App\Models\Password_manager\Password_manager;

    use Windwalker\Crypt\Password;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    class Password_manager extends Model{

  

        public static function create($password){
            

            if(empty($password)){ return false; }
            
    
            $pw = new Password;

            $pass = $pw->create($password);

             
            return $pass;
            
        }



        public static function check($password,$hash){
            

            if(empty($password) or empty($hash)){ return false; }
            
            $pw = new Password;

            $bool = $pw->verify($password, $hash);

            
            return $bool;	
            
        }


        public static function validate($password,$password_min_length = 6){

            if(empty($password)){ return false; }

            if(strlen($password) < $password_min_length){

                return false;

            }

            return true;

        }
        
    }


?>