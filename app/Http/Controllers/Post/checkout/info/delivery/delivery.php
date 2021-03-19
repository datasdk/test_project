<?php




    $delivery_accessible = Delivery::accessible();

    $pickup_all_day = Settings::get("pickup_all_day");

    $delivery_all_day = Settings::get("delivery_all_day");
  


    $delivery_active = Settings::get("delivery_active");

    $pickup_active = Settings::get("pickup_active");


    $processing_time_minutes = Settings::get("processing_time_minutes");

    $pickup_minutes_in_advance = Settings::get("pickup_minutes_in_advance");
    
    $minutes_in_advance = 0;


    // DELIVERY PICKUP 



    $pickup_sap = Settings::get("pickup_sap");

    $pickup_options_set_time = Settings::get("pickup_options_set_time");



    $delivery_sap = Settings::get("delivery_sap");

    $delivery_options_set_time = Settings::get("delivery_options_set_time");


    // shipping 

    $shipping_active = Shipping::active();


    


    $lang = Languages::lang_url();

    $return_url = $lang . "/checkout/info";


    $success = true;

    $sap = 0;

    
    
    if(empty($_POST["delivery_type"]) and ($delivery_active or $shipping_active) ){ 


        $msg = Sentence::translate("Please specify a delivery type"); 
        
        echo json_encode( array("error"=>true, "msg"=>$msg) );
        
        exit();
    
    } 
    
    else 
    
    if(empty($_POST["payment_type"])){


        $msg = Sentence::translate("Please specify a payment type"); 
        
        echo json_encode( array("error"=>true, "msg"=>$msg) );
        
        exit();


    }

    else
    
    {


        $payment_type = $_POST["payment_type"];
        
        if(empty($_POST["delivery_type"])){ $delivery_active = false; } 

        
        if(isset($_POST["delivery_type"]))
        if($delivery_active OR $pickup_active OR $shipping_active){

     


            $type       = $_POST["delivery_type"];


            if(isset($_POST[$type])){
               
                $token      = $_POST[$type]["token"];
                $timestamp  = $_POST[$type]["timestamp"];
                $delay      = $_POST[$type]["delay"];


                if(isset($_POST[$type]["option"])){

                    $option     = $_POST[$type]["option"];

                }
                
              

                // sæt standardsværdo for bookingstart, hvis de øvrige options er deaktiveret
                $booking_start = $timestamp;

            }
            
            // DELIVERY
            //is the option accessible?

                
                $accessible = false;

                

                if(Shipping::is_shipping($type)){
                                    
                    
                    $accessible = true;

                    $option = "sap";                    

                 

                }

                else

                if($delivery_accessible["ok"] and $type == "delivery"){

                    $accessible = true;

                    // sæt behandlingstid i munutter til levering
                    $minutes_in_advance = $processing_time_minutes;


                    if(empty($option)){


                        if($delivery_sap){

                            $option = "sap";

                        }
                        
                        if($delivery_options_set_time){

                            $option = "time";
                            
                        }
                        

                    }

                }

                else 
                
                if($pickup_active and $type == "pickup"){

                    $accessible = true;

                    // sæt behandlingstid i minutter for afhenting
                    $minutes_in_advance = $pickup_minutes_in_advance;


                    if(empty($option)){

                        
                        if($pickup_sap){

                            $option = "sap";

                        }
                        
                        if($pickup_options_set_time){

                            $option = "time";
                            
                        }
                        

                    }


                }



                // if option is empty after validateing

        

                if(!$accessible){


                    $msg = Sentence::translate("The selected delivery type is not available"); 
                    
                    echo json_encode( array("error"=>true, "msg"=>$msg) );
                    
                    exit();

                }
                

                
                if(isset($option)){

        
                    if($option == "sap"){

                        $sap = 1;

                        // sæt booking start til nuværende tidspunkt + behandlingtid 
                        $booking_start = time() + ($minutes_in_advance * 60);

                    }

                    else

                    if($option == "time"){

                        $sap = 0;


                        // hvis afhentningstidspunkt er valg, skal bookingstart sætte det det valgt tidspunkt i kalenderen
                        $booking_start = $timestamp;

                        // DATE

                        if(empty($_POST[$type]["date"])){
                            

                            $msg = Sentence::translate("Please specify a date");
                            
                            echo json_encode( array("error"=>true, "msg"=>$msg) );
                            
                            exit();

                            
                        } 

                        // TIME

                        if(empty($_POST[$type]["time"])){


                            $stop = false;


                            if($type == "delivery"){

                                if(!$delivery_all_day){

                                    $msg = Sentence::translate("Please specify a delivery time");

                                    $stop = true;

                                }

                            }


                            if($type == "pickup"){

                                if(!$pickup_all_day){

                                    $msg = Sentence::translate("Please specify a pickup time");
                                
                                    $stop = true;

                                }
                                
                            }


                            
                            if($stop){

                                echo json_encode( array("error"=>true, "msg"=>$msg) );
                            
                                exit();

                            }
                            

                        } 

                    }                
                
                }


        }
        

    }


  
    include(__DIR__."/includes/update_database.php");



    





?>