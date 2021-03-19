<?php 

    namespace App\Models\Customers;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use Cookie;


    class Customers extends Model{
        
        
        public static function get($customer_ref_id = 0,$add_specifications = true,$groupe_name = false){


            if($customer_ref_id !== "*")            
            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();
          
            }

    

            if(!$customer_ref_id){

                return false;

            }


            $customer = array();

            // CUSTOMER

            $sql = "select * from customers";
            
            $sql .= " where 1 ";


            if($customer_ref_id != "*"){

                $sql .= " and id = '".$customer_ref_id."' ";

            }

            
            if($groupe_name){
                
                $groupe_ref_id = self::get_group_by_name($groupe_name);

                $sql .= " and customer_groupe_ref_id = '".$groupe_ref_id."' ";

            }

            
        
     
            $result = DB::select($sql);

  
     

            if($add_specifications){

              
                foreach($result as $id => $val){


                    
                    $result2 = self::get_specification($id);

                 
                    if(!empty($result2))
                    foreach($result2 as $val2){


                        $name = $val2["name"];

                        $value = $val2["value"];

                        $result[$id]["specifications"][$name] = $value;


                    }

                }

            }

         
            
            if(empty($result)){
                
                return false;

            }


       
            if($customer_ref_id != "*"){

                $result = Format::current($result);

            }
            



            // NEWSLETTER

            $sql = "select * from customers_newsletters";

            $r = DB::select($sql);


            foreach($r as $customer_id => $val){
              
                $result[$customer_id]["accept_newsletters"]     = $val["accept_newsletters"];
                $result[$customer_id]["accept_sms_newsletters"] = $val["accept_sms_newsletters"];

            }
                  
          

            return $result;


        }

        
        public static function id(){

            return self::getCustomerId();

        }


        public static function getCustomerId(){


            $customer_ref_id = Cookie::get("customer_login_id");


            if(!empty($customer_ref_id)){


                $sql = "select * from customers where id = '".$customer_ref_id."'";

                
                if(empty(DB::numrows($sql))){
    
                    Cookie::remove("customer_login_id");
    
                    return false;
    
                }
    
    
                return $customer_ref_id; 


            }
           
            
        }


        public static function get_group($customer_ref_id = 0,$type = "id"){


            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }


            if(!$customer_ref_id){

                return false;

            }

            
            $c = self::get($customer_ref_id);
            

            if($c){

                return $c["customer_groupe_ref_id"];

            }


            return false;
            

        }
        

        public static function connect_to_api($customer_ref_id = 0){


            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }
            

            $c = self::get($customer_ref_id) ;

            

            if(!$c){

                return false;

            }


            if($c){
                    
                    
                $s = $c["specifications"];
                

                

                if(isset($s["api_key"]) and isset($s["secret"])){


                    $api_key = $s["api_key"];
                    $secret  = $s["secret"];

      
                    return Api::login($api_key,$secret);;


                }
                

            }


            return false;

        }

        
        public static function get_group_by_name($name){


            $group_ref_id = 0;


            if(empty($name)){

                return 0;

            }


            $sql = "
            select * from customers_groups
            where name = '".$name."' ";
             
        
            $r = Format::current( DB::select($sql) );

            if($r){

                $group_ref_id =  $r["id"];

            }


            return $group_ref_id;


        }


        public static function in_group($customer_ref_id = 0,$group_name){


            $g = self::get_group($customer_ref_id);

            $g2 = self::get_group_by_name($group_name);

     

            if($g == $g2){

                return true;

            }

            return false;

        }

        
        public static function login_by_id($customer_ref_id){


            $r = Cookie::set("customer_login_id",$customer_ref_id);	
    
            
            return $r;
            

        }



        public static function get_customer_id_by_email($email){

            
            if(empty($email)){ return false; }


            $sql = "
            select id,email from customers 
            where email = '".$email."' limit 1";

            $result = Format::current( DB::select($sql) );
            
            if(empty($result)){

                return false;


            }

            return $result["id"];

        }


        public static function login($email,$password){


    
            $sql = "
            select * from customers 
            where 
            email = '".$email."' limit 1";
            
            $result = current(DB::select($sql));
            
            
            if($result){
                
                

                if(Password_manager::check($password,$result["password"])){
                   
                    $customer_ref_id = $result["id"];
    
                    self::login_by_id($customer_ref_id);


                    return $customer_ref_id;

                }

            }
    
            
            return false;

        }



    

        public static function is_logged_in($group_name = 0){


            if($group_name){ 

                $gid = self::get_group_by_name($group_name);
                
                $group_ref_id = Session::get("customer_login_group");
                

                if($group_ref_id != $gid){

                    return false;

                }

            }
            

            $id = Cookie::get("customer_login_id");
            
            

            if($id){ return true; }

            return false;
            

        }


        public static function customer_logoff(){


            return Cookie::remove("customer_login_id");
            

        }


      

        public static function add($company,$cvr,$ean,$firstname,$lastname,$address,$housenumber,$floor,$zipcode,$city,$phone,$email,$password=false,$active=1,$note=""){

          
            if(empty($password)){ 
                
                $password = Format::strtolower(substr($firstname,0,2).substr($lastname,0,3).rand(100, 999)); 
            
            }


            $password = Password_manager::create( $password  );

            $customer_number = uniqid();

            

            if(self::exists_by_email($email)){

                return false;

            }

            

            $arr = array("company" => $company,
                         "customer_number" => $customer_number,
                         "cvr" => $cvr,
                         "firstname" => $firstname,
                         "lastname" => $lastname,
                         "address" => $address,
                         "zipcode" => $zipcode,
                         "housenumber" => $housenumber,
                         "floor" => $floor,
                         "city" => $city,
                         "phone" => $phone,
                         "email" => $email,
                         "date"=>time(),
                         "password"=>$password,
                         "note"=>$note,
                         "active"=>$active
                        );
            
            return DB::insert("customers",$arr);
               
            
        }


        public static function updateaaaaaa($options = []){



            $customer_ref_id = false;
            $company = false;
            $cvr  = false;
            $firstname = false;
            $lastname = false;
            $address = false;
            $housenumber = false;
            $floor = false;
            $zipcode = false;
            $city = false;
            $phone = false;
            $email = false;
            $password = false;
            $note = false;


            extract($options);


            if(empty($options) or
            !$customer_ref_id){ return false; }



            if(!empty($password)){

                $password = Password_manager::create( $password  );
            
            }
            
            
            if(!$customer_ref_id)
            if(!self::exists($customer_ref_id)){

                return false;

            } 
            
     
/*
            $check_customer_ref_id = self::get_customer_id_by_email($email);
          

            if($check_customer_ref_id)
            if($check_customer_ref_id != $customer_ref_id){

                return false;

            }

            */
         

            $c = [];
            
    
                
            if($company){       $c[]= "company='".$company."'"; }
            if($cvr){           $c[]= "cvr='".$cvr."'"; }
            if($firstname){     $c[]= "firstname='".$firstname."'"; }
            if($lastname){      $c[]= "lastname='".$lastname."'"; }
            if($address){       $c[]= "address='".$address."'"; }
            if($zipcode){       $c[]= "zipcode='".$zipcode."'"; }
            if($housenumber){   $c[]= "housenumber='".$housenumber."'"; }
            if($floor){         $c[]= "floor='".$floor."'"; }
            if($city){          $c[]= "city='".$city."'"; }
            if($phone){         $c[]= "phone='".$phone."'"; }
            if($email){         $c[]= "email='".$email."'"; }
            if($note){          $c[]= "note='".$note."'"; }
            if(!empty($password)){ $c[]= "password='".$password."'"; }

            
        

            if(!empty($c)){


                $sql = "update customers set ";

                $sql .= " ".implode(",",$c)." ";

                $sql .= " where id = '".$customer_ref_id."' ";
          

                DB::update($sql);

            }
            

            return true;

        }


        public static function RemoveCustomer($customer_ref_id){

            $sql = "delete from customers where id = ".$customer_ref_id;

            return DB::delete($sql);

        }


        public static function exists($customer_ref_id = 0){

            
            $sql = "select * from customers where id = '".$customer_ref_id."'";


            if(DB::numrows($sql) > 0){
    
                return true;
    
            } 

            return false;

        }


        public static function exists_by_email($email){

            
            $sql = "select * from customers where email = '".$email."'";


            if(DB::numrows($sql) > 0){
    
                return true;
    
            } 

            return false;

        }


        public static function exists_by_name($name){

            
            $sql = "select * from customers where firstname = '".$name."'";


            if(DB::numrows($sql) > 0){
    
                return true;
    
            } 

            return false;

        }


        public static function has_newsletter($customer_ref_id = 0){

            
            $customer = self::get($customer_ref_id);

            
            if(!empty($customer)){

                if($customer["accept_newsletters"]){

                    return true;

                } 

            }


            return false;

        }
       

        public static function send_welcome_email($customer_ref_id,$password,$hide_password=true){

            // MY COMPANY


            $sql = "select * from company";

            $result = current(DB::select($sql));

            $my_company = $result["name"];


            // CUSTOMER
            
            $customer = self::get($customer_ref_id);


            $firstname  = $customer["firstname"];
            $lastname   = $customer["lastname"];
            $email      = $customer["email"];
            

            $email_text = Settings::get("text_new_customer_welcome_mail");


            if($hide_password)
            $password = str_repeat("*",strlen($password));

            

            $content = "
            <strong>Velkommen til ".$my_company.".</strong> 

           ".$email_text."

            <i>Du skal bruge følgende e-mail og adgangskode for at logge ind:</i>
            <strong>E-mail:</strong> ".$email."
            <strong>Adgangskode:</strong> ".$password."

            <i>Brugerkonto</i>
            Du kan logge ind med din bruger på vores hjemmeside eller
            via dette link: <a href='".$_SERVER['HTTP_HOST']."/account'>min profil</a>
            
            <i>Vilkår og persondata</i>
            Du kan læse om dine brugervilkår og vores håndtering af persondata under dette link:
            <a href='".$_SERVER['HTTP_HOST']."/terms'>Brugervilkår og persondata-håndtering</a> 

            Med venlig hilsen
            <strong>".$my_company."</strong>
            ";
                
            
            Email::send($email,"Velkommen til ".$my_company,nl2br($content),0,1);


        }

      
        
     



        public static function specification_exists($customer_ref_id, $name){


            $sql = "
            select id from customers_specifications
            where
            customer_ref_id = '".$customer_ref_id."' and 
            name = '".$name."'
            ";
            

            if(DB::numrows($sql)){

                return true;

            }


            return false;


        }



        public static function set_specification($customer_ref_id, $name, $value,$cal=false){

            

            $sql = "
            select id from customers_specifications 
            where name = '".$name."' and customer_ref_id = '".$customer_ref_id."'";


            if(DB::numrows($sql)){


                $sql = "
                update customers_specifications 
                set 
                
                value = 
                ";

                if($cal == "++"){ $sql .= " value + "; }

                if($cal == "--"){ $sql .= " value - "; }

            

                $sql .= "
                
                '".$value."'

                where 
                customer_ref_id = '".$customer_ref_id."' and
                name = '".$name."'";

                DB::update($sql);

                


            } else {


                $arr = [
                "customer_ref_id" => $customer_ref_id,
                "name" => $name,
                "value" => $value
                ];


                DB::insert("customers_specifications",$arr);

            }

            
            return $value;
            
        }



        public static function card($customer_ref_id = 0){
            

            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }
       
            if(!$customer_ref_id){ return false; }


            $is_localhost = DB::is_localhost();


            $sql = "
            select * from customers_ccrg 
            where 
            customer_ref_id = '".$customer_ref_id."' and 
            localhost = '".$is_localhost."'
            ";
           

            $r = Format::current( DB::select($sql) );


            return $r;
    

        }

      

        public static function get_specification($customer_ref_id = 0,$variable_name = false){

          
            

            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }

            if(!$customer_ref_id){

                return false;

            }


            
            $sql = "
            select * from customers_specifications 
            where 
            customer_ref_id = '".$customer_ref_id."' 
            ";

            if($variable_name){

                $sql .= " and name = '".$variable_name."' ";

            }

            $sql .= " order by sorting";

       

            $result = DB::select($sql);


            if(empty($result)){

                return false;

            }

            
            if($variable_name){

                
                $arr = [];


                if($result){
        
                    foreach($result as $val){

                        $arr[]= $val["value"];

                    }
                    
                }


                if(empty($arr)){

                    return false;

                }

                             

                if(count($arr) == 1){ 
                
                    return current($arr);
                
                }

                

                return $arr;

            }
            



            return $result;
            

        }



        public static function set_ccrg($customer_ref_id,$ccrg,$card_hint = "000000",$card_type = ""){
            

            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }
            

            $card_type = strtolower($card_type);

          

            $is_localhost = DB::is_localhost();


            $sql = "
            select id from customers_ccrg 
            where 
            customer_ref_id = '".$customer_ref_id."' and
            localhost = '".$is_localhost."'";


            $r = DB::numrows($sql);

            $is_localhost = DB::is_localhost();



            if(!$r){


                $arr = [
                "customer_ref_id"=>$customer_ref_id,
                "card_type"=>$card_type,
                "card_hint"=>$card_hint,
                "ccrg"=>$ccrg,
                "remeber_card"=>1,
                "localhost" => $is_localhost
                ];
            

                DB::insert("customers_ccrg",$arr);


            } else {


                $sql = "
                update customers_ccrg 
                set
                
                ccrg = '".$ccrg."',
                card_hint = '".$card_hint."',
                card_type = '".$card_type."'

                where 
                customer_ref_id = '".$customer_ref_id."'";


                DB::update($sql);


            }

        }




        public static function get_ccrg($customer_ref_id = 0){
            

            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }
       
            if(!$customer_ref_id){ return false; }

            
            $is_localhost = DB::is_localhost();


            $sql = "
            select * from customers_ccrg 
            where 
            customer_ref_id = '".$customer_ref_id."' and
            localhost = '".$is_localhost."'";

        

            $r = Format::current( DB::select($sql) );


            if(!$r){ return false; }


            $ccrg = $r["ccrg"];

            if(empty($ccrg)){ return false; }
            
            
            return $ccrg;


        }



        public static function remove_ccrg($customer_ref_id = 0){
            

            if(!$customer_ref_id){

                $customer_ref_id = self::getCustomerId();

            }
            

            if(!$customer_ref_id){ return false; }


            $is_localhost = DB::is_localhost();


            $sql = "
            delete from customers_ccrg 
            where 
            customer_ref_id = '".$customer_ref_id."' and 
            localhost = '".$is_localhost."'";


            return DB::delete($sql);


        }


        public static function params($customer_ref_id,$param = []){


            $c = self::get($customer_ref_id);


            $r = [];

            if(!is_array($param)){ $param = [$param]; }

   
            if($c)
            foreach($param as $val){

                if(isset($c[$val])){

                    $r[$val]= $c[$val];

                } else {

                    $r[$val]= false;

                }

            }
            

            if(empty($r)){ return false; }


            return $r;

        }
        

        public static function overview($arr = []){
            

            $customer_ref_id = "*";
            $add_specifications = true;
            $groupe_name = false;
            $description = "";
            $button_text = "Læs mere";
            $link = "";
            

            $result = [];

            extract($arr);


            $r = self::get($customer_ref_id,$add_specifications,$groupe_name);

     
            
            if(empty($r)){ return false; }


            $base_link = $link;


            if($r)
            foreach($r as $val){


                if(!empty($link)){

                    $link = $base_link."?customer_ref_id=".$val["id"];

                }
                

                $result[] = [
				"title" => $val["firstname"]." ".$val["lastname"],
				"description" => $description,
				"button_text" => $button_text,
                "image_ref_id" => $val["image"],
                "link" => $link,
				"new_window" => 0,
				"image_cover" => 1,
				"public_date" => time(),
                ];

            }
            
                        
            Information::custom($result);


        }


    }

?>