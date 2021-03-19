<?php
    
    $delivery_type = "";


    $order_ref_id = Order::get_order_id(); 

    $order = Order::get($order_ref_id);


    if($order){

        $delivery_type = $order["delivery_type"];

    }

    

    $package_store = Shipping::is_package_store($delivery_type);

    $lang = Languages::lang_url();



    if($has_customer){

?>



    <div class="sektion">


        <div class="content">


            <?php

                if(!empty($_GET["msg"])){

                    echo "<div class='alert alert-success'>";
                    
                    echo Sentence::translate("You are now logged in");
                    
                    echo "</div>";

                }



                include(__DIR__."/includes/login.php");


                include(__DIR__."/includes/formular.php");
       
                
                include(__DIR__."/includes/another_delivery_address.php");

                        
            ?>


        </div>

    </div>

  
<?php                          

    }

?>













