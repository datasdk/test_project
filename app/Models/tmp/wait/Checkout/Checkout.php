<?php

    namespace App\Models\Checkout;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    class Checkout extends Model{

        
        public static $page;
        public static $arg;

        
        public static function insert($arr = []){
    
            


            $return_url = "/cart";
            $page = self::$page;

            $has_payment = true;
            $has_delivery = true;
            $has_customer = true;
            $has_menu = true;

            $require_account = false;
            $delivery = true;
            $customer = true;
            $form = true;
            $has_navigation = true;
            $redirect = true;
            $address = true;
            $has_login = true;
            $has_address = true;
            $has_newsletter = true;
            $add_customer = true;
            $has_comment = true;

            extract($arr);
            
            

            echo "<div class='checkout-wrapper";
                

                if($has_menu){

                    echo " flex ";

                }
            

            echo "'>";



                if($has_menu){ echo "<div class='content_wrapper'>"; }
                
        
                
                    if($page == "reciept"){


                        include(__DIR__."/includes/reciept/reciept.php");


                    } else {



                        if($redirect){ 
                            
                            self::check_order(); 
                        
                        }
                        
                        

                        if($page == "delivery"){
                           
                            Delivery::insert();
                            
                        }

                        else

                        if($page == "customer"){
                    
                           include(__DIR__."/includes/info/customer/customer.php");
                            
                        }

                        else

                        if($page == "info"){
                    
                            include(__DIR__."/includes/info/info.php");

                        }
                        
                        else
                        
                        if($page == "accept"){

                            if($redirect){ self::check_info(); }

                            include(__DIR__."/includes/accept/accept.php");

                        }
                    
                        else
        
                        if($page == "stripe"){

                            include(__DIR__."/includes/payment/creditcard/creditcard.php");

                        }

                        else

                        if($page == "mobilepay"){

                            include(__DIR__."/includes/payment/mobilepay/mobilepay.php");

                        } 

                        else

                        if($page == "mobilpay_complete"){

                            include(__DIR__."/includes/complete/mobilepay.php");

                        } 

                        else
                            
                        if($page == "complete"){


                            if($redirect){ 
                            
                                self::check_info();
                                self::check_accept();
                                self::check_payment();

                            }

                            
                            include(__DIR__."/includes/complete/complete.php");


                        } 


                    }
                    
            

                if($has_menu){


                    echo "</div>";


                    echo "<div class='checkout_navibar'>";

                        
                        include(__DIR__."/includes/includes/shop_cart.php");


                    echo "</div>";


                }
               


            echo "</div>";


        }
        

        public static function navigation($back_url,$next_button = true){


            echo '<div class="navigation">';

            
                echo '                                        
                <a href="'.$back_url.'" class="checkout-back-btn">
                '.Sentence::translate("Back").'
                </a>
                ';
                    
                
                if($next_button){

                    echo '                                  
                    <button type="submit" class="checkout-next-btn">     
                    '.Sentence::translate("Next page").'
                    </button>
                    ';

                }
                
                
            echo '</div>';


        }



        public static function check_order(){


            $order = Order::get_order_id();

            $products = Order::load_products();
           

            $enable_shop = Settings::get("enable_shop");     

            if(!$enable_shop){
               
        
                header('location: /');
        
                exit;
        
            }

            
            if(empty($order) or empty($products)){
                
             

                header("location: ".$lang_url."/");

                die();

            }


            

        }


        public static function check_info(){
            

            $lang_url = Languages::lang_url();

            $order = Order::get();

            $delivery_active = Settings::get("delivery_active");



            if(empty($order["payment_type"]) 
            or 
            (empty($order["delivery_type"]) and $delivery_active)){

                header("location: ".$lang_url."/checkout/info");

                die();

            }


            
            if(
            empty($order["firstname"]) or 
            empty($order["lastname"]) or
            empty($order["email"]) or
            empty($order["phone"])){

                header("location: ".$lang_url."/checkout/info");
                
                die();

            }


        }
        
     

        public static function check_accept(){

            $lang_url = Languages::lang_url();

            $order = Order::get();


            if(empty($order["accepted_terms_of_trade"])){

                header("location: ".$lang_url."/checkout/accept");
                
                die();

            }

        }



        
        public static function check_payment(){



            $order = Order::get();

            $lang_url = Languages::lang_url();



            if(
                $order["payment_type"] == "stripe" or 
                $order["payment_type"] == "mobilepay"
                ){
                

                if(empty($order["is_paid"])){



                    if($order["payment_type"] == "stripe"){

                        header("location: ".$lang_url."/checkout/payment/creditcard");
                        
                        die();

                    } 
                    
                    else 
                    
                    if($order["payment_type"] == "mobilepay"){

                        header("location: ".$lang_url."/checkout/payment/mobilepay");
                        
                        die();

                    }

                    else 
                    {

                        header("location: ".$lang_url."/checkout");
                        
                        die();

                    }


                    die();

                }

            }

        }


        public static function check_complete(){


            $order = Order::get();

            $lang_url = Languages::lang_url();


            if(empty($order["order_done"])){

                header("location: ".$lang_url."/checkout/info");
                
                die();

            }

            
        }


        public static function delivery_has_choices(){


            $sql = "select id from settings_payment where active = 1";

            $payments = DB::numrows($sql);


            $delivery_active = Settings::get("delivery_active");

            $pickup_active = Settings::get("pickup_active");


            if(count($payments) > 2 OR ($delivery_active AND $pickup_active)){


                return true;


            }

            return false;

        }


        public static function get_section_by_name($name,$params = []){
            

            $p = ["page"=>$name,"redirect"=>false];

            $p = array_merge($p,$params);
            
            self::insert($p);


        }


        public static function reciept($params = []){
        
            self::get_section_by_name("reciept",$params);
            

        }

    
        public static function info($params = []){

            self::get_section_by_name("info",$params);

        }

        

        public static function accept($params = []){

            self::get_section_by_name("accept",$params);

        }


        public static function creditcard($params = []){

            self::get_section_by_name("stripe",$params);

        }


        public static function mobilepay($params = []){

            self::get_section_by_name("mobilepay",$params);

        }


        public static function complete($params = []){
        
            self::get_section_by_name("complete",$params);

        }


 
        

    }

?>