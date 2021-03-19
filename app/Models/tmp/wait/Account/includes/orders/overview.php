<?php

    include(__DIR__."/includes/header.php");    

?>


<div class="account_title">
    <h3>Mine ordrer</h3>
    <p>Her kan du ændre dine brugeroplysninger</p>
</div>



<div class="acccount_content">

    <?php
        
        if(empty($result)){

            
            echo "<div class='alert alert-primary'>";
            
            echo Sentence::translate("You have not made any bookings via this user account");

            echo "</div>";


            
        } else {


            $order_id_array = array_keys($result);


            if(!empty($order_id_array)){


                echo "<table id='account-order-overview' class='table'>";

                
                echo "<tr>";

                    echo "<th>Dato</th>";

                    echo "<th class='hidden-xs'>Betalingstype</th>";

                    echo "<th>Status</th>";
 
                    echo "<th align='right'>Pris ekskl. moms</th>";

                echo "</tr>";


                echo "<tbody>";

                    
                    foreach($order_id_array as $order_ref_id){


                        $order = Order::get($order_ref_id);
                        
                        $prices = Order::load_prices($order_ref_id);


                        $reference_number = $order["reference_number"];

                        $date = $order["date"];

                        $payment_type = Payment::translate($order["payment_type"]);

                        $is_paid = $order["is_paid"];
                        

                        $total = $prices["sum"] + $order["delivery_price"] + $order["fee"] - $order["discount"];

                        $total = Format::number($total,1)." kr.";


                        $payment_status = "Ikke betalt";

                        
                        if($is_paid){ $payment_status = "Betalt"; }



                        $lang_url = Languages::lang_url();

                        $url =  $lang_url."/account/preview/".$order_ref_id;

                        


                        echo "<tr data-url='".$url."'>";

                            //echo "<td class='hidden-xs'>".my_orders_set_link($order_ref_id,$reference_number)."</td>";

                            echo "<td>".date("d/m Y - H:i",$date)."</td>";

                            echo "<td class='hidden-xs'>".($payment_type)."</td>";

                            echo "<td class='nowrap'>".($payment_status)."</td>";
                            
                            echo "<td align='right'>".($total)."</td>";

                        echo "</tr>";
        
                    }

                    

                    echo "</tbody>";
        

                echo "</table>";

            }

           
            
        }


      

    ?>


</div>