<?php
	
    namespace App\Models\Delivery\Delivery;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



	class Delivery extends Model{
        

        public static function load($type){


            $interval = array();

            $mysqli = DB::mysqli();


            if($type == "delivery"){


                include(__DIR__."/includes/delivery.php");


            }
        

            if($type == "pickup" or $type == "booking" ){


                include(__DIR__."/includes/pickup.php");


            }
            

            return $interval;

        }


        public static function insert(){

 

            include(__DIR__."/includes/insert/header.php");


            echo '<div class="sektion-title">';
                        
                echo Sentence::translate("CHOOSE DELIVERY");

            echo '</div>';



            echo '<div class="delivery_wrapper">';


                // delivery
                include(__DIR__."/includes/insert/delivery.php");


                // shipping
                include(__DIR__."/includes/insert/shipping.php");


                // pickup                        
                include(__DIR__."/includes/insert/pickup.php");
                                    


                $lang = Languages::lang_url();


            echo "</div>";

           

            echo Shipping::insert();


        }


        public static function get_time_interval($type){



            if($type == "delivery"){

               return Settings::get("delivery_interval_time");

            }
        

            if($type == "pickup" or $type == "booking" ){

                return Settings::get("pickup_interval_time");
                
            }


        }


        public static function get($type){
            

            $obj = self::load($type);

            include(__DIR__."/includes/list.php");


        }


        public static function is_delivery($delivery_type){


            $types = ["delivery","GLSDK_HD","GLSDK_BP","PDK_PPR","PDK_BP"];

            if(in_array($delivery_type,$types)){

                return true;

            }

            return false;


        }


        public static function price(){
            

            $type = Settings::get("delivery_type");

            $delivery_standard_price = Settings::get("delivery_standard_price");

            $prices = array();
        
        

            if($type == "radius"){
        
        
                $sql = "select * from delivery_radius";

                $result = DB::select($sql);
        

                foreach($result as $val){
            
                    $prices[]= $val["price"];
            
                }



                $content = "<span class='float-left'>".Sentence::translate("Delivery")."</span>";

                $content .= "<span class='float-right'>";

                if(count($prices) > 1){ $content .=  Sentence::translate("Fra")." "; }

               // sa($prices);

                $content .=  Format::number(min($prices));
                
                
                $content .= "</span>";


                return $content;


        
            } else if($type == "zipcode"){
         
        
                $sql = "
                select * from delivery_areas 
                
                inner join delivery_areas_prices
                on delivery_areas.zipcode = delivery_areas_prices.zipcode_ref_id

                order by delivery_areas.zipcode
                ";

                $result = DB::select($sql);

                $content = ""; 



                foreach($result as $val){
            
                   

                    $content .= "<div class='block'>";

                    $content .= "<span class='float-left'>".$val["zipcode"]." ".Zipcodes::get($val["zipcode"])."</span>";

                    $content .= "<span class='float-right'>";
    

                    if(count($prices) > 1){ $content .=  Sentence::translate("Fra")." "; }
    
                    
                    $content .=  Format::number($val["price"]);
                    
                    $content .= "</span>";  


                    $content .= "</div>";

            
                }

               

                return $content;

        
            } else if($type == "everywhere"){


                $content = "";

                $content .= "<div class='block'>";

                $content .= "<span class='float-left'>I hele Danmark</span>";

                $content .= "<span class='float-right'>".Format::number($delivery_standard_price)."</span>";

                $content .= "</div>";


                return $content;

            }
    

        }


        public static function accessible(){



            $delivery_active = Settings::get("delivery_active");
            $delivery_free_delivery_active = Settings::get("delivery_free_delivery_active");
            $delivery_free_delivery_over_amount = Settings::get("delivery_free_delivery_over_amount");
            $delivery_min_buy_for_delivery = Settings::get("delivery_min_buy_for_delivery");
        
            $sum = Order::load_prices()["sum"];
        
        

            if($delivery_active){
                

                if($sum < $delivery_min_buy_for_delivery){

                    $diff = $delivery_min_buy_for_delivery - $sum;
                    
                    $msg = "<strong class='pr-1'>".Sentence::translate("Information")."</strong> ".Sentence::translate("We offer delivery upon purchase over")." <strong>".$delivery_min_buy_for_delivery." kr.</strong> (".Sentence::translate("You need")." ".$diff." kr.)";
                    
                    return array("ok"=>false,"msg"=>$msg);
        
                }


                if($delivery_free_delivery_active){
        
                    if($sum < $delivery_free_delivery_over_amount){
                        
                        $diff = $delivery_free_delivery_over_amount - $sum;

                        $msg = "<strong class='pr-1'>".Sentence::translate("Information")."</strong>".Sentence::translate("Information")." We offer free delivery upon purchase over <strong>".$delivery_free_delivery_over_amount." kr.</strong> (".Sentence::translate("You need")." ".$diff." kr.)";
                        
                        return array("ok"=>true,"msg"=>$msg);
        
                    }
        
                } 
        

            } 
            
            else 
            
            {

                return array("ok"=>false,"msg"=>"");

            }
            

            return array("ok"=>true,"msg"=>"<strong class='pr-1'>Information</strong>".Sentence::translate("We offer delivery"));


        }


        public static function get_delivery_time($day = 0){


                     
            $sql = "select * from delivery_time ";


            if(!$day){

                $day = date("N");

                $sql .= " where day = '".$day."' ";

            }


            $val = Format::current(DB::select($sql));


            $to_hours = $val["to_hours"];
            $to_minutes = $val["to_minutes"];

            $today_end_time = strtotime($to_hours.":".$to_minutes);
        

            return $today_end_time;


        }


        public static function translate($delivery_type){

            if($delivery_type == "delivery"){   return Sentence::translate("Delivering to the address"); }
            if($delivery_type == "pickup"){     return Sentence::translate("Pick-up from the company"); }
            if($delivery_type == "booking"){    return Sentence::translate("Booking"); }



            if(Shipping::is_shipping($delivery_type)){


                $r = Shipping::get();


                foreach($r as $val){

                    if($val["code"] == $delivery_type){

                        $description = Sentence::get($val["description"]);
                    
                        return $description;

                    }

                }

                
            }

           
            return $delivery_type;

        }
        

        public static function settings($type){


            $is_booking = false;
            $all_day = true;
            $time_frame = false;
            $time_interval = 15;
            $minutes_in_advance = 15;


            if($type == "booking"){

                $is_booking  = true;

                //$all_day = 1;
                //$info = 0;
                //$time_interval = 500;

            }
            

            // LOAD INFORMATION

            if($type == "pickup" or $is_booking){    


                $all_day             = Settings::get("pickup_all_day");

                $time_frame          = Settings::get("pickup_timeframe");
                
                $time_interval       = Settings::get("pickup_interval_time");

                $minutes_in_advance  = Settings::get("pickup_minutes_in_advance");


            

            }
            
            else

            if($type == "delivery"){  
                

                $all_day            = Settings::get("delivery_all_day");

                $time_frame         = Settings::get("delivery_timeframe");
                
                $time_interval      = Settings::get("delivery_interval_time");

                $minutes_in_advance = Settings::get("deivery_minutes_in_advance");


            }


            return ["all_day"=>$all_day,"time_frame"=>$time_frame,"time_interval"=>$time_interval,"minutes_in_advance"=>$minutes_in_advance];

        }


    }

?>