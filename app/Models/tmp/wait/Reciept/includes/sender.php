
<div class="fromto">


    <div class="col-left">

            <?php if($second_address): ?>
            
            <div>
            <strong>
                
                <?php 
      
                    echo Sentence::translate($sender_title1); 
                    
                ?>
                
            </strong>
            </div>

            <?php endif; ?>

            <div><?php echo $customer["company"]; ?></div>
            <div><?php echo $customer["firstname"]." ".$customer["lastname"]; ?></div>
            <div><?php echo $customer["address"]." ".$customer["housenumber"]." ".$customer["floor"]; ?></div>
            <div><?php echo $customer["zipcode"]." ".$customer["city"]; ?></div>
            

            <?php if($second_address): ?>
            <br>
            <div><strong><?php echo Sentence::translate("Delivery address"); ?></strong></div>
            <div><?php echo $second_address["company"]; ?></div>
            <div><?php echo $second_address["firstname"]." ".$second_address["lastname"]; ?></div>
            <div><?php echo $second_address["address"]." ".$second_address["housenumber"]." ".$second_address["floor"]; ?></div>
            <div><?php echo $second_address["zipcode"]." ".$second_address["city"]; ?></div>
            <?php endif; ?>

            <?php if($customer["address"]){ echo "<br>"; }?>

            
            <div><?php echo Sentence::translate("E-mail"); ?>: <?php echo $customer["email"]; ?></div>
            <div><?php echo Sentence::translate("Phone"); ?>: <?php echo "<a href='tel:".$customer["phone"]."'>".$customer["phone"]."</a>"; ?></div>
            <div><?php echo $customer["cvr"]; ?></div>

    </div>


    <div class="col-right">

            <div><?php echo $company["company"]; ?></div>
            <div><?php echo $company["address"]." ".$company["housenumber"]." ".$company["floor"]; ?></div>
            <div><?php echo $company["zipcode"]." ".$company["city"]; ?></div>
            <div><?php echo $company["cvr"]; ?></div>

    </div>


</div>



<div class="order-info">


<?php


    $sql = "select * from order_booking where order_ref_id = '".$order_ref_id."'";

    $booking = current(DB::select($sql));


    $employee_ref_id = $booking["employee_ref_id"];
    $booking_start = $booking["booking_start"];
    $booking_end = $booking["booking_end"];
    $sap = $booking["sap"];


    $delivery_type = $order["delivery_type"];
    $payment_type = $order["payment_type"];



    $is_package_store = Shipping::is_package_store($delivery_type);

    $delivery_type    = Delivery::translate($delivery_type);

    $payment_type     = Payment::translate($payment_type);



    echo "
    <div>
    <span>".Sentence::translate("Payment type").":</span> 
    <strong>".$payment_type."</strong>
    </div>";


      
    if(!empty($delivery_type)){


        echo "
        <div>
        <span>".Sentence::translate("Delivery type").":</span> 
        <strong>".$delivery_type."</strong>
        </div>";


        if(!$is_package_store)
        if($sap)
        { 
                
            echo "<div><span>".Sentence::translate("Time").":</span> <strong>".Sentence::translate("As soon as possible")."</strong> </div>"; 

        }

        else 

        {


            if($booking_start){ 


                $timetxt = Sentence::translate("Time");

                if($booking_start and $booking_end){ $timetxt = Sentence::translate("Period"); }

                echo "<div><span>".$timetxt."</span>";
                
                echo date("d/m/y H:i",$booking_start);                 

               

                if($booking_end){ 
                                                
                  
                    if(strtotime("midnight",$booking_start) != strtotime("midnight",$booking_end)){

                        echo " - ".date("d/m/y H:i",$booking_end);
                        
                    }
                        
                   
                                            
                }
                    
                    
                echo "</div>"; 
                
            }


        }


    }




    $reciept_note = Settings::get("reciept_note");



    if($order["comment"] or $reciept_note){


        echo "<div class='comment'>";

        
            if($reciept_note){

                $reciept_note = Sentence::get($reciept_note);
                

                if($reciept_note){

                    echo "<i><strong>".Sentence::translate("Note").": </strong> ".Sentence::get($reciept_note)."</i>";

                }
 
            }


            if($order["comment"]){

                echo "<i><strong>".Sentence::translate("Comment").": </strong> ".ucfirst($order["comment"])."</i>";
            
            }

        
        echo "</div>";

    }
    

?>


</div>
