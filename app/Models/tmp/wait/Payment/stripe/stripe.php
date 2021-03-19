<?php

    class Stripe{
        

        public static $stripe = 0;
        public static $account_id = 0;
        public static $persons_id = 0;
        public static $public = 0;
        public static $secret = 0;


        public static function connect(){


            if(!empty(self::$stripe)){ return self::$stripe; }




            
            if(DB::is_localhost() or Payment::is_test()){

                self::$public = 'pk_test_W2dRwfk6VPJok6nyk8YzX9Jn00hUmPEmYv';
                self::$secret = 'sk_test_CAhXW4EEbJhHicp3zWGOe6Gc00Hj1350pp';

            } else {

                self::$public = 'pk_live_6QuoktYb85caGe981gnUkFPM00r7hq379o';
                self::$secret = 'sk_live_Jd5f88TDnHaotvNTbM3WRPxQ00fDrI7Umm';

            }

    

            // SMARTIFART

            

            
            $stripe = new \Stripe\StripeClient(self::$secret);

            return $stripe;


        }


        public static function get_key(){

            self::connect();

            return self::$public;

        }





        public static function insert($options = []){


            
            $customer_ref_id = 0;
            $onsubmit = "";
            $product_ref_id = 0;
            $empty_cart = false;
            $order_ref_id = 0;
            $type = "payment";
            $account_id = 0;
            $description = "test payment";
            $return_url = false;
            $return_url = false;
            $amount = 300;
            $method = "post";
            $button_text = false;
            $form = true;
            $order_required = true;
            $has_card_save = true;
            $wrapper = true;
            $has_cart = true;
            $has_terms = false;
            $order_amount = 0;
            

            extract($options);

          

            // payment

            $stripe = self::connect();

            $public_key = self::get_key();
          


            
            $r = self::get_active_card();

            $active_card = false;


            if($r){


                $active_card = $r["active_card"];
                $payment_methode = $r["payment_methode"];
                $last4 = $r["last4"];
                $brand = $r["brand"];
                

            }
  



            if($type == "save_card"){


                $title = Sentence::translate("Save creditcard");

                if(!$button_text){ $button_text = Sentence::translate("SAVE CARD"); }

                $action = "/stripe/subscribe_card";


            }   
 
            else

            if($type == "payment"){


                $title = Sentence::translate("Online payment");

                if(!$button_text){ $button_text = Sentence::translate("PAY NOW"); }

                $action = $return_url;
                
            }


 
                           
            if($has_card_save)
            if($active_card){
    
        
                include(__DIR__."/includes/save_card.php");

            } 
           

            include(__DIR__."/includes/payment.php");
     


            
            if($has_cart){


                $p = [
                    "header"=>0,
                    "has_products"=>0,
                    "booking"=>0,
                    "button"=>0,
                    "hide_completed"=>1
                ];

                echo Cart::overview($p);

            }



           


            include(__DIR__."/includes/stripe_info.php");


  
        }



        public static function input($options = []){

            $options["form"] = false;

            self::insert($options);

        }


        public static function create_payment_intent($options = []){
        

            $customer_ref_id = 0;
            
            $pi = 0;
            $product_ref_id = 0;
            $order_ref_id = 0;
            $type = "payment";
            $account_id = 0;
            $description = "test payment";
            $amount = 300;
            $order_required = true;
            $order_amount = 0;
            $subtotal  = 0;
            $last4 = "";
            $brand = "";
            $save_card = false;
            $confirm = false;
            $ccrg = Customer::get_ccrg();
            $payment_methode = false;
            $active_card = false;
            $cardholder = "";


            extract($options);
           


       
            $cu = Customer::params($customer_ref_id,["firstname","lastname"]);
          

            try {



                $account_id = Variable::get("stripe_account_id");
                
                if(!$account_id){ 
                    
                    return ['success'=>false,"title"=>"Error","msg"=>"No account connected"]; 
                
                }
            

                $stripe = self::connect();


                // if customer does not have a saved card
                // create new customer

                if(empty($ccrg)){
                    
                    $ccrg = self::create_customer();               
                    

                } else {
            

                    // get active card form customer
                    $r = self::get_active_card();


                    if($r){

                        $active_card = $r["active_card"];
                        $payment_methode = $r["payment_methode"];
                        $last4 = $r["last4"];
                        $brand = $r["brand"];

                    }
                
            
                }
            


                // get subtotal of order

                if($order_amount){

                    $subtotal  = $order_amount * 100;

                } 
                
                else {


                    if($product_ref_id){


                        $order_ref_id = Order::create();


                        if($empty_cart){

                            Cart::empty();

                        }


                        $pa = Products::add_to_cart(["product_ref_id"=>$product_ref_id]);
                        

                        if(empty($pa["success"])){

                            return $pa;

                        }


                    }

                    else

                    if($order_required){
                    
                        if(!$order_ref_id){ $order_ref_id = Order::get_order_id();}

                        if(!$order_ref_id){ 
                            
                            return ['success'=>false,"title"=>"Error","msg"=>"no order found"]; 
                        
                        }

                    }
                    

                    
                    $pi = Order::get_specification($order_ref_id,"stripe_payment_id"); 
                    
                    $p = Order::load_prices($order_ref_id);

                    $subtotal   = $p["subtotal"] * 100;


                }

                
                if($subtotal < 250){ 

                    return ['success'=>false,"title"=>"error","msg"=>"Amount must be at least 2.50 kr dkk"];

                }

            

                if($pi){
                        
                    // hvis man køber flere gange i shoppen kan sidste pi risikere at blive fjernet
                    // lav derfor paymentcheck på ordre id
                    //self::refunds(["pi"=>$pi]);

                }



                $fee_percent = 0.6;

                $fee_cash = 0;

            // $f            = Api::request("/stripe/get_application_fee");



                if(!empty($f["fee_percent"])){

                    $fee_percent = $f["fee_percent"];

                }
                

                if(!empty($f["fee_cash"])){
                
                    $fee_cash = $f["fee_cash"];

                }
                
                

                

                $application_fee = ( $subtotal / 100 * $fee_percent ) + $fee_cash;
                
                

                $p = [
                    'amount' => $subtotal,
                    'currency' => 'dkk',
                    'payment_method_types' => ['card'],
                    'setup_future_usage' => 'off_session',
                    'capture_method' => 'manual',
                    //'confirmation_method' => 'manual',
                    //'application_fee_amount'=>$application_fee
                    //'amount_capturable' => 00
                    'metadata' => ['integration_check' => 'accept_a_payment'],
                    ];


                    if($payment_methode){

                        $p['payment_method'] = $payment_methode;

                    }

                    
                    if($ccrg){

                        $p['customer'] = $ccrg;

                    }


                    if($account_id){

                        $p['transfer_data']['destination'] = $account_id;

                        $p['on_behalf_of'] = $account_id;

                    }
            

           
                try {
             

                    $pk = $stripe->paymentIntents->create($p);


                } catch (Exception $e) {

                    
                    //$e->getError()->code


                    $title = "The payment could not be completed";

                    $msg   = "The card information may be entered incorrectly";


                    $title = Sentence::translate($title);

                    $msg   = Sentence::translate($msg);

    
                    return ["success"=>false,"title"=>$title,"msg"=>$msg];
                    

                }

                
                
                $client_secret = $pk->client_secret;
                $pi = $pk->id;


/*
                // confirm 
                $ca = [
                        "pi"=>$pi,
                        "ccrg"=>$ccrg,
                        "save_card"=>$save_card
                    ];


                self::confirm($ca);
*/



                //return 
                
                $r = [
                    "success"=>true,
                    "pi"=>$pi,
                    "client_secret"=>$client_secret,
                    "amount"=>$subtotal,
                    "order_ref_id"=>$order_ref_id,
                    "active_card"=>$active_card,
                    "last4"=>$last4,
                    "brand"=>$brand,
                    "payment_methode"=>$payment_methode,
                    "cardholder"=>$cardholder                   
                ];


                return $r;



            } catch (\Stripe\Exception\CardException $e) {
                
                
                $msg = $e->getError()->code;

                return ["success"=>false,"title"=>"Error","msg"=>$msg];

                
            }
            

        }


        public static function is_paid($pi){


            $stripe = self::connect();


            try{


                $res = 
                $stripe->paymentIntents->retrieve($pi,[]);



            } catch (Exception $e) {


                return false;


            }
            

            return true;

        }


        public static function close($options = []){

        

            $return_url = Page::get_full_url();
            $customer_ref_id  = 0;
            $order_ref_id = 0;
            $fee = 0;
            $pi = 0;
            $save_card = false;
            $button_text = "Betal nu";
            $capture = false;
            $fee_percent = 0;
            $fee_cash = 0;
            $pi = false;
            $ccrg = false;
            $close_order = true;


            extract($options);
        
           

            $stripe = self::connect();

        
            
            if(!self::is_paid($pi)){  

                return ["success"=>false,"msg"=>"Order is not paid"];

            }



            
            if($save_card)
            if(Customer::is_logged_in($customer_ref_id)){

                
                $ccrg= self::get_customer_id($customer_ref_id);

                Customer::set_ccrg($customer_ref_id,$ccrg);


            } 


            
            Order::set_info(["is_paid"=>1]);

            Order::set_specification($order_ref_id,"stripe_payment_id",$pi);   



            $arr = 
            [
                'success'=>true,
                'id'=>$pi,
                'ccrg'=>$ccrg,
                'save_card'=>$save_card,
               // 'application_fee_amount'=>$fee,
                'currency' => 'dkk',
                'status' => "Amount is reserved and ready to capture"
            ];
            

            return $arr;


        }



        public static function get_customer_id($customer_ref_id = 0){


            $is_deleted = false;

            $stripe = self::connect();



            if(!$customer_ref_id){

                $customer_ref_id = Customer::getCustomerId();

            }
            

            if(!$customer_ref_id){

                return false;

            }


            $stripe_customer_id =
            Customer::get_specification($customer_ref_id, "stripe_customer_id");   

              
        
            try {
            

                $ex = 
                $stripe->customers->retrieve($stripe_customer_id,[]);

                $is_deleted = $ex->deleted;
               

            } catch (Exception $e) {

                return false;       

            }


 

            if($is_deleted){ return false; }

            if(empty($stripe_customer_id)){ return false; }


            return $stripe_customer_id;


        }



        public static function save_card($options = []){


            $pi = 0;
            $customer_ref_id  = 0;
            $method = "get";
            


            extract($options);


            $stripe = self::connect();

        

            if(!$customer_ref_id){

                $customer_ref_id = Customer::getCustomerId();

            }



            if(!$customer_ref_id){

                return false;

            }



            $amount = 300;
          
            
            $r = 
            self::confirm(["pi"=>$pi]);

    
            /*

            $p = self::capture(["pi"=>$pi]);
      

            if(!$p){ return false; }
            */

 

                     
            self::refunds(["pi"=>$pi]);



            return ["success"=>true,"id"=>$pi];


        }
        
        

        public static function create_customer($option = []){
            

            try {


                $token = 0;
                $customer_ref_id = 0;


                extract($option);
    


                if(!$customer_ref_id){

                    $customer_ref_id = Customer::getCustomerId();

                }            

                
                if(!$customer_ref_id){

                    return false;

                }
            

                $stripe_customer_id= self::get_customer_id($customer_ref_id);
                
        

                if($stripe_customer_id){

                    return $stripe_customer_id;

                }


                $stripe = self::connect();


                $c = Customer::get($customer_ref_id);


                $p = [
                    'name' => $c["firstname"]." ".$c["lastname"],
                    'email' => $c["email"],
                    'phone' => $c["phone"],
                    'description' => "Customer ID: ".$customer_ref_id." - DATE: ".date("d/m Y H:i"),
                ];



                if($token){

                    $p['source'] = $token;

                }



                try {
             

                    $res = $stripe->customers->create($p);


                } catch (Exception $e) {
    
                    return false;
                    
                }
                


              

                $stripe_customer_id = $res->id;          


               
                self::set_customer_id($customer_ref_id,$stripe_customer_id);


                return $stripe_customer_id;



            } catch (Exception $e) {

                    

            }



        }


        public static function set_customer_id($customer_ref_id,$stripe_customer_id){

            return Customer::set_specification($customer_ref_id, "stripe_customer_id", $stripe_customer_id);     

        }


 


        public static function capture($option = []){
            
            $order_ref_id = 0;
            $pi = 0;
            $amount_capturable = 0;

            
            extract($option);


            if(empty($pi)){ return false; }

     
            $stripe = self::connect();
                       

        
            try {
             

                $p = [];
                
                if($amount_capturable){ $p['amount_capturable'] = $amount_capturable; }
                

                $c = 
                $stripe->paymentIntents->capture($pi,$p);



            } catch (Exception $e) {

                return $e->getError()->code;
                
            }



            if($c){

               // Order::remove_specification($order_ref_id,"stripe_payment_id");  

            }


            return $c;


        }



        public static function refunds($option = []){


            $pi = 0;
            $amount = 0;

            extract($option);


            if(empty($pi)){ return false; }
            

            $stripe = self::connect();
                       

            try {
             

                $c = 
                $stripe->paymentIntents->cancel($pi,[]);


            } catch (Exception $e) {

                return $e->getError()->code;
                
            }
     


            return $c;

        }


        public static function payment_exists($order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }


            if(!$order_ref_id){

                return false;

            }


            $pi = 
            Order::get_specification($order_ref_id,"stripe_payment_id");   


            if(!$pi){   



            }



            if($pi){ return $pi; }


            return false;


        }


        public static function update($order_ref_id = 0){

    
            $p = [
            "order_ref_id"=>$order_ref_id,
            ];
    
            $r = self::prepare($p);

            return $r;
            

        }


        public static function transfer($option = []){


            $order_ref_id = 0;
            $amount = 0;
 

            extract($option);

            
            $stripe = self::connect();


            $account_id = Variable::get("stripe_account_id");
                

            if(!$account_id){ 
                
                return ['success'=>false,"title"=>"Error","msg"=>"No account connected"]; 
            
            }

    


            $o = Order::get($order_ref_id);

            if(empty($o)){ 
                
                return ['success'=>false,"title"=>"Error","msg"=>"Could not be transferred. order is missing"];  
            
            }



            $invoice_number = $o["invoice_number"];

            $price = Order::load_prices();
            
            $host = $_SERVER["HTTP_HOST"];

            if(!$amount){ $amount = $price["subtotal"]; }
            

            

            try {
             

                $res = 
                $stripe->transfers->create([
                    'amount' => $amount,
                    'currency' => 'dkk',
                    'destination' => $account_id,
                    'transfer_group' => $host." - ORDER: ".$invoice_number,
                ]);
              

            } catch (Exception $e) {

                return ['success'=>false,"title"=>"Error","msg"=>$e->getError()->code];
                
            }
        

        }

        
        public static function intents($option = []){


          

        }


        public static function get_active_card($ccrg = 0){


            $active_card = false;


            if(!$ccrg){ $ccrg = Customer::get_ccrg(); }
            
            if(!$ccrg){ return false; }


            $stripe = self::connect();



            try {
             

                $pm = 
                $stripe->paymentMethods->all([
                    'customer' => $ccrg,
                    'type' => 'card',
                ]);


            } catch (Exception $e) {

                return false;
                
            }
            
                
            if(isset($pm->data[0])){


                $ac = $pm->data[0];
                $payment_methode = $ac->id;
                $last4 = $ac->card->last4;
                $brand = $ac->card->brand;

                $active_card = true;


            } else {

                return false;

            }


            $r = [
                    "active_card"=>$active_card,
                    "payment_methode"=>$payment_methode,
                    "last4"=>$last4,
                    "brand"=>$brand
                ];


            return $r;

        }

/*




        public static function create_account($option = []){


            $stripe = self::connect();


            $failure_url = Page::get_full_url();
            $success_url = Page::get_full_url();
    
            extract($option);

  
            
            $account_id = Variable::get("stripe_account_id");



            if(!$account_id){

                $account_id = $stripe->accounts->create();

                Variable::set("stripe_account_id",$account_id);

            }
            
  
            $a = 
            $stripe->accountLinks->create([
                'account' => $account_id,
                'failure_url' => $failure_url,
                'success_url' => $success_url,
                'type' => 'custom_account_verification',
                'collect' => 'eventually_due',
            ]);



            header("location: ".$a->url);

            die();


        }



   public static function insert_save_card($options = []){



        }
     

        public static function add_card_to_customer($options = []){


            $customer_ref_id = 0;

            $number = '4242424242424242';
            $exp_month = '6';
            $exp_year = '2021';
            $cvc = '314';


            extract($options);


            self::connect();


            $stripe_customer_id = 
            self::get_customer_id($customer_ref_id);


            if(!$stripe_customer_id){

                return false;

            }


            $t =
            \Stripe\Token::create([
                'card' => [
                  'number' => $number,
                  'exp_month' => $exp_month,
                  'exp_year' => $exp_year,
                  'cvc' => $cvc,
                ],
            ]);

        
            $token = $t->id;
   

            
            $stripe = new \Stripe\StripeClient(self::$secret);
            
            $c = 
            $stripe->customers->createSource(
                $stripe_customer_id,
                ['source' => $token]
            );


            $stripe_card_id = $c->id;


            Customer::set_specification($customer_ref_id,"stripe_card_id",$stripe_card_id);


            return $stripe_card_id;

        }




        public static function subscripe($options = []){


            $customer_ref_id  = 0;

            extract($options);

            self::connect();



            $product_id = 
            \Stripe\Product::create([
                'name' => 'Gold Special',
            ]);
              


            $price_id = 
            \Stripe\Price::create([
                'unit_amount' => 2000,
                'currency' => 'dkk',
                'recurring' => ['interval' => 'month'],
                'product' => $product_id,
            ]);




            
            $stripe_customer_id = self::get_customer_id($customer_ref_id);

            
            $c = 
            \Stripe\Subscription::create([
            'customer' => $stripe_customer_id,
            'items' => [['price' => $price_id]],
            ]);



            sa( $c);


        }



        
       

        
        public static function pay_by_customer_card($options = []){


            $order_ref_id = 0;
            $customer_ref_id = 0;
            $amount = 1000;
            $currency = 'dkk';


            extract($options);


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();     

            }
            

            if(!$customer_ref_id){

                $customer_ref_id = Customer::getCustomerId();

            }
            
            
            if(!$customer_ref_id){

                $customer_ref_id = Customer::get_customer_by_order_id($order_ref_id);

            }


            if(
                !$order_ref_id or 
                !$customer_ref_id
            ){ 

                return false;

            }



            $stripe_card_id = 
            Customer::get_specification($customer_ref_id,"stripe_card_id");
            

            if(!$stripe_card_id){ return false; }



            self::connect();


            $p =
            \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $stripe_card_id,
                'description' => 'My First Test Charge (created for API docs)',
            ]);



            $stripe_payment_id = $p->id;
            
            Order::set_specification($order_ref_id,"stripe_payment_id",$stripe_payment_id);



            return $stripe_payment_id;



        }


        public static function create_account($option = []){


            self::connect();


            $customer_ref_id = 0;

            $mcc            = "5969";

            $company        = "Kaspers firma";

            $firstname      = 'Kasper';
            $lastname       = 'Kristiansen';
            $address        = "Dalvangsvej 13 st. 6";
            $postal_code    = "2600";
            $city           = "Glostrup";
            $email          = 'kasper@livebuy.dk';
            $state          = 'Sjælland';

            $phone          = '+4582828217';
            $tax_id         = '40070818';
            $country        = 'DK';
            $currency       = 'DKK';

            $account_number = "DK5000400440116243";
            $website        = "datas.dk";

            $birth_day      = 22;
            $birth_month    = 12;
            $birth_year     = 1988;



            extract($option);

            /*
            if(!$customer_ref_id){

                $customer_ref_id = Customer::getCustomerId();

            }
            

            if(!$customer_ref_id){

                return false;

            }
     
            
            $account_id = Variable::get("stripe_account_id");


            if($account_id){

                return $account_id;

            }
            
  

            $account = \Stripe\Account::create([
              'country' => 'DK',
              'type' => 'custom',
              'business_type'=>'company',
              'email'=>$email,
              'requested_capabilities' => ['card_payments', 'transfers'],
              'company' => [
                  'name'=>$company,
                  'address'=>[
                      'line1'=>$address,
                      'postal_code'=>$postal_code,
                      'city'=>$city,
                      'state'=>$state
                  ],
                  'phone'=>$phone,
                  'tax_id'=>$tax_id,
                  'directors_provided'=>true,
                  'owners_provided'=>true,
                ],
                'external_account'=>[
                    'object'=>'bank_account',
                    "status" => "new",
                    'country'=>$country,
                    'currency'=>$currency,
                    'account_holder_name'=>$firstname." ".$lastname,
                    'account_number'=>$account_number
                ],
                'business_profile'=>[
                    'url'=>$website,
                    'support_url'=>$website,
                    'mcc' => $mcc
                ],
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                ]             
            ]);


            $account_id = $account->id;
            self::$account_id = $account_id;
 

           
            $persons = \Stripe\Account::createPerson(
                $account_id,
                [
                    'first_name' => $firstname, 
                    'last_name' => $lastname,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => [
                        'line1'=>$address,
                        'postal_code'=>$postal_code,
                        'state'=>$state,
                        'city'=>$postal_code,
                        'country'=>"DK",
                    ],
                    'relationship' => [
                        'representative'=>true,
                        'director'=> true,
                        'owner'=> true,
                        'title'=>'developer',
                        'percent_ownership'=>"100"
                    ],
                    'dob' => [
                        'day'=>$birth_day,
                        'month'=>$birth_month,
                        'year'=>$birth_year
                    ]
                ]
            );

            

           // self::$persons_id = $persons->id;

            // Fetching an account just needs the ID as a parameter
            $r = \Stripe\Account::retrieve($account_id);


            Variable::set("stripe_account_id",$account_id);


            $account_links = \Stripe\AccountLink::create([
                'account' => $stripe_account_id,
                'failure_url' => 'https://example.com/failure',
                'success_url' => 'https://example.com/success',
                'type' => 'custom_account_verification',
                'collect' => 'eventually_due',
            ]);



            return $account_links;


        }
*/

    }


?>