<div id="shopmondo" class = "shipping-wrapper">


    <div class="shipping-header">


    
    </div>


    <div class="shipping-content">

        <?php


            $sp = 0;


            if($order_ref_id){

                $o = Order::get_alternative_delivery_address($order_ref_id);

                $sp = $o["shipping_point"];

            }



            $max = 4;
            $i  = 0;

        //    if(isset($r["output"]))
            foreach($r["output"] as $val){
                

                $i++;

                if($i > $max){ break;}

                
                $id             =  $val["id"];
                $company_name   =  $val["company_name"];
                $address        =  $val["address"];
                $zipcode        =  $val["zipcode"];
                $city           =  $val["city"];
                $country        =  $val["country"];
                $opening_hours  =  $val["opening_hours"];

                $carrier_code   =  $val["carrier_code"];

                
                $shipping_point = Shipping::get_shipping_point();
              
        ?>


                <div class="shipping-item">


                    <div class="input">

                        <?php

                            $sel = "";

                            if($id == $sp){

                                $sel = "selected";

                            }
                        
                        ?>
                            
                        <input type="radio" name="shipping_point" value="<?php echo $id; ?>"
                        <?php if($shipping_point == $id){ echo ' class=" active " ';} ?>
                        <?php echo $sel; ?>
                        >
                        
                    </div>


                    <div class="address">

                        <strong><?php echo $company_name; ?></strong>
                        <p><?php echo $address; ?></p>
                        <p><?php echo $zipcode." ".$city ; ?></p>
            
                    </div>
                        

                    <div class="opening_hours">

                        <?php 

                            $prev_time = "";
                            $days = [];
                            
                            $block = 0;
                            $oh = [];

                            


                            foreach($opening_hours as $val2){
                                    

                                $day  = str_replace(":","", explode(" ",$val2)[0] );

                                $time = str_replace("-"," - ",explode(" ",$val2)[1]);

                                $day  = Format::strtolower($day);

                             

                                if($prev_time != $time){

                                    $block++;
                                    $prev_time = $time; 

                                }    
                                

                                $oh[$block][] = ["day" => $day, "time" => $time];


                            } 

                       
                            

                            foreach($oh as $val){


                                $from       = current($val);
                                $to         = end($val);
                      
                               

                                echo "<div class='line'>";
                                            

                                    if($to == $from){

                                        echo "<span class='day'>".$from["day"]."</span>";

                                    } else {

                                        echo "<span class='day'>".$from["day"]." - ".$to["day"]."</span>";

                                    }
                                    

                            
                                    echo "<span class='time'>".$from["time"]."</span>";

                                            
                                echo "</div>";



                            }

                            /**
                             *  
                                        
                             * 
                             */

                        ?>

                    </div>


                </div>


        <?php

            }

        ?>

    </div>


    <div class="shipping-fotter">

        

    </div>


</div>