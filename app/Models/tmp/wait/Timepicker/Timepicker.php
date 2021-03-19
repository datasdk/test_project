<?php

    Class Timepicker{
 
        	
        public static function  insert( $arr = []) {
                
        
            $name = false;

            $default_timestamp = time();

            $type = false;

            $product_ref_id = 0;

            $persons = 0;

            $min_timestamp = time();

            $time_type = "select";
        
            $is_booking  = false;

            $time_frame = false;

            $active = 0;

            $from_time = "00:00";

            $to_time = "23:59";

            $time_interval = 15;

            $now = 0;
            
            $mn = strtotime("midnight");

            $onchange = false;

            


            extract($arr); 

            

            if(!$now){ $now = time(); }

            

            
            
            $skip_to_next_day = false;

                

            if(!$name){ return false; }
            if(!$type){ return false; }

          
            if($default_timestamp < $min_timestamp){ $default_timestamp = $min_timestamp; }
     
            
          


               $interval = self::get_time_interval($type,$default_timestamp);

               $processing_time_minutes = Settings::get("processing_time_minutes");

                
                $settings            = Delivery::settings($type);

                $all_day             = $settings["all_day"];

                $time_frame          = $settings["time_frame"];
                
                $time_interval       = $settings["time_interval"];

                $minutes_in_advance  = $settings["minutes_in_advance"];
        
            

                $count_interval = (60 * $time_interval);
         
    
                if(empty($count_interval)){
    
                    $count_interval = 15 * 60;
    
                }

                

            // START

      
            $time_values = [];

       

            ob_start();
           


                echo "<div class='datepicker-time-wrapper'>";



                    for($i = $interval["from"]; $i <= ($interval["to"]); $i += $count_interval){


                
                        $clock = date('H:i', $i);

        

                        $start_timestamp = strtotime($clock,$default_timestamp);

                        $end_timestamp   = strtotime($to_time,$start_timestamp);

                        $add = true;    
                        

                        if(!isset($first_val)){ $first_val = $start_timestamp; }
                        


                        if($type != "standard"){

                            
                            if($start_timestamp < $min_timestamp){ continue; }
                        
                            if($now  > $start_timestamp){ continue; }
                           
                            if($processing_time_minutes)
                            if(($now + ($processing_time_minutes) * 60) > $start_timestamp){ continue; }
                                

                        }

                        
                
                        if($is_booking){

                            
                            $options = [
                                "product_ref_id"=>$product_ref_id,
                                "booking_start"=>$start_timestamp,
                                "booking_end"=>$end_timestamp
                            ];

                            // if product is reserved by another. skip to next time interval
                            if(!Booking::is_available($options)){ continue; }
                            
                        }




                        include(__DIR__."/includes/standard.php");

                        include(__DIR__."/includes/pickup.php");

                        include(__DIR__."/includes/delivery.php");
                        
                        include(__DIR__."/includes/booking.php");

                      
                        

                        if($add){


                            $no_times = false;

                            $delay = 0;



                            if($time_frame){ 
                                

                                $time = date("H:i",$start_timestamp);
                                
                                
                                if($end_timestamp)
                                if($start_timestamp == $end_timestamp){ continue; };

                                
                                //echo date("d/m Y H:i",$timestamp)." < ".date("d/m Y H:i",$now)."<br>";

                                //if($timestamp < $now){ continue; }

                                
                                if($start_timestamp + $count_interval > $end_timestamp){

                                    $time .= " - ".date("H:i",$end_timestamp); 

                                } else {

                                    $time .= " - ".date("H:i",$start_timestamp + $count_interval); 

                                }

                                
                                $delay = $count_interval;


                            }
                            else 
                            { 
                                
                                $time = date("H:i",$start_timestamp); 
                            
                            }



                            $class = "";

                            if($delay){

                                $class = "delay";

                            }

                        
                            // 
                            
                        
                            if($persons){

                                if(!Booking::room_for_persons($product_ref_id,$start_timestamp,$persons)){

                                    continue;

                                }

                            }
                            


                            if($time_type == "blocks"){

                                
                                echo '
                                <div class="datepicker-time-option '.$class.'">

                                    <a href="javascript:" 
                                    class="time_'.$start_timestamp.'"
                                    data-time="'.$clock.'"
                                    data-timestamp="'.$start_timestamp.'"
                                    data-delay="'.$delay.'"
                                    >'.$time.'
                                    </a>
                                
                                </div>
                                ';


                            } else {

                                $time_values[$start_timestamp] = $clock;

                            }
                            

                        }

                    
                }


 

                 if(empty($time_values)){ 
                    
                  //  return false;

                //    $time_values[$first_val] = date("H:i",$first_val); 
                
                }

            


                    include(__DIR__."/includes/timepicker.php");
                



                echo "</div>";


                $content = ob_get_contents();


            ob_end_clean();

            
            echo $content;

            
        }



        public static function get_minutes($time_string) {

            $parts = explode(":", $time_string);
            
            if(count($parts) > 1){

                $hours = intval($parts[0]);
                $minutes = intval($parts[1]);
            
                return $hours * 60 + $minutes;

            }

            return $time_string;
            
        }



        public static function get_time_interval($type,$default_timestamp) {


            $mn = strtotime("midnight");

            $is_booking = false;
            $from_time = "00:00";
            $to_time = "23:59";
            $all_day  = false;
       



            if($type == "booking"){

                $is_booking  = true;

                //$all_day = 1;
                //$info = 0;
                //$time_interval = 500;

            }
            

            // LOAD INFORMATION

            if($type == "pickup" or $is_booking){    

                $info = Delivery::load("pickup"); 
        
            }
            
            else

            if($type == "delivery"){  
                
                $info = Delivery::load("delivery"); 

            }



            // SET VARIABLES

            $day_number = date("N",$default_timestamp);
        
            $is_today = ( $mn == strtotime("midnight",$default_timestamp) );


            $OpeningHours = OpeningHours::load();

            $openingtime = current($OpeningHours)[$day_number]["from"];

            $closingtime = current($OpeningHours)[$day_number]["to"];



            if(isset($info)){

                $active             = $info[$day_number]["active"];

                $from_time          = $info[$day_number]["from"];

                $to_time            = $info[$day_number]["to"];

            }

            


            $from = strtotime($from_time,$default_timestamp);

            $to   = strtotime($to_time,$default_timestamp);


            return ["from"=>$from,"to"=>$to];

        }


    }

?>