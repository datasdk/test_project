<?php

    namespace App\Models\Datepicker\Datepicker;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    class Datepicker extends Model{


        public static function insert($arr = []){
            

            $name = "datepicker";
            $type = "standard";
            $default_timestamp = 0;
            $min_timestamp = 0;
            $product_ref_id = 0;
            $persons = 0;
            $timepicker = false;
            $callback = "";
            $onload = "";
            $time_type = "select";
            $connect_with = false;
            $connect_difference = false;
            $next_day_if_empty = false;

            $product_ref_id = 0;
            $persons = 0;
            $skip_today = 0;
            $now = 0;


      
            //$now = strtotime("26-7-2020 9:00:00");
                   
 
            extract($arr);

            
            if(!$now){ $now = time(); }

            if(empty($name)){ return false; }


            // booking skal være det samme som pickupdate.. ind til videre
            if($type == "booking"){ $type = "pickup"; }



            $datepicker_id = uniqid();
            $closingdays = array();
            
            $days_in_advance = Settings::get("processing_time_days"); 

           
            if(!$default_timestamp){    $default_timestamp = $now ; }
            if(!$min_timestamp){        $min_timestamp = $now ; }
            
            if($default_timestamp < $now){  $default_timestamp = $now; }
            if($min_timestamp < $now){      $min_timestamp = $now; }


            // opening hours

            $processing_time_minutes = intval(Settings::get("processing_time_minutes")) * 60;
            
            $min_timestamp += $processing_time_minutes;

            
            $openinghours = OpeningHours::get(1);
            $closingdays = array();
            

      

            // divs

            echo "
            <div id='".$datepicker_id."' 
            class='datepicker_wrapper'             
            >";

            
                if($type == "pickup"){    
                    
                    include(__DIR__."/includes/pickup.php");
                
                }


                if($type == "delivery"){  
                    
                    include(__DIR__."/includes/delivery.php"); 
                
                }
              

                // CLOSING DAYS        

                include(__DIR__."/includes/special_closing_days.php");

                include(__DIR__."/includes/closing_days.php");
        

              
                while(true){


                    $day_number = date("N",$default_timestamp);

                    if($day_number == 7){ $day_number = 0; }
                    

                    if(isset($closingdays[$day_number]))
                    if(!$closingdays[$day_number] or in_array($default_timestamp,$special_closing_days)){
                        
                        $default_timestamp = strtotime("next day midnight",$default_timestamp);

                        continue;

                    }

                    break;


                }


                

                $interval = Timepicker::get_time_interval($type,$default_timestamp);

                $settings = Delivery::settings($type);



                $all_day             = $settings["all_day"];

                $time_frame          = $settings["time_frame"];
                
                $time_interval       = $settings["time_interval"];

                $minutes_in_advance  = $settings["minutes_in_advance"];



                $count_interval = (60 * $time_interval);
             
                if(empty($count_interval)){
    
                    $count_interval = 15 * 60;
    
                }
                

            

             
                if($next_day_if_empty)
                if($min_timestamp >= $today_end_time){
                    

                    $min_timestamp = strtotime("midnight +1 day");
                    
                  
                    if($default_timestamp < $min_timestamp){
            
                        $default_timestamp = $min_timestamp;
            
                    }
                   
                }

                  
         
                if($now > $interval["to"]){

                    $min_timestamp = strtotime("midnight + 1 day");

                }                              

       
                include(__DIR__."/includes/datepicker_timepicker.php");



            echo "</div>";





        }



        public static function datepicker_to_timestamp($dp_obj,$delivery_type){

            
            $booking_start = false;


            $d = "";

            $t = "00:00";
    

            if(isset($_POST[$delivery_type]["datepicker"])){

                $d = $_POST[$delivery_type]["datepicker"];

            }

            if(isset($_POST[$delivery_type]["timepicker"])){

                $t = $_POST[$delivery_type]["timepicker"];

            }

    

            $booking_start = strtotime( trim($d." ".$t) );
                    
      


            return $booking_start;


        }


    }

?>