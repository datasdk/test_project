<?php

    if($delivery_type == "delivery")
    if(isset($_POST["address"]))
    if(isset($_POST["zipcode"]))
    if(isset($_POST["city"])){

        
        $delivery_price = 0;
        $distance = 0;

        $address = $_POST["address"];
        $zipcode = $_POST["zipcode"];
        $city = $_POST["city"];

        $arr = Company::get();
        

        $from = $arr["address"].",".$arr["zipcode"]."".$arr["city"]." denmark";

        $to   = $address.",".$zipcode."".$city." denmark";

        

        $result = RadiusDelivery::get_distance($from,$to);
        
     
        if($result)
        $distance = $result["distance"]["value"] / 1000;
        
        $result2 = RadiusDelivery::get_price_by_distance($distance);



       if($result2 == "too_long"){

        
            $msg = Sentence::translate("We do not deliver in this area, please choose another delivery address");

            echo json_encode(array("error"=>true,"msg"=>$msg,"url"=>$return_url));
            
            die();

        }

        $delivery_price = $result2; // price;
        

    }

?>