<?php
    
    $product_ref_id = 0;
    $persons = 0;


    if(isset($_POST["product_ref_id"])){

         $product_ref_id = $_POST["product_ref_id"];

    }
        

    if(isset($_POST["persons"])){

        $persons = $_POST["persons"];
            
    }

        
    Booking::insert_date_option($product_ref_id,$persons);

    
?>
