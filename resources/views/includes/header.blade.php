
<?php

    $info = Company::get();

    

    $order_ref_id = Orders::get_order_id();

    $order = Orders::get();
    

    $from = time();

    $to = time();



    if($order){

      //  $from   = $order["booking_start"];

      //  $to     = $order["booking_end"];

    }



    $from   = date("d-m-Y",$from);

    $to     = date("d-m-Y",$to);


?>
    
    <div class="navigationbar-top-wrapper">

        <?php
        
        
            echo Components::navigationbar("index",["logo"=>0]);
            
            
        //    echo Languages::insert(["float"=>"right"]);
            
            
            echo Components::navigationbar("login");
        
        ?>
        
    </div>