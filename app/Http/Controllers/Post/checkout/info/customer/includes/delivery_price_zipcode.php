<?php


    if($delivery_type == "delivery")
    if(isset($_POST["address"]))
    if(isset($_POST["zipcode"]))
    if(isset($_POST["city"])){



        $zipcode = $_POST["zipcode"];


        $sql = "
        select * from delivery_areas 
                
        inner join delivery_areas_prices
        on delivery_areas.zipcode = delivery_areas_prices.zipcode_ref_id

        where 
        delivery_areas.zipcode = '".$zipcode."' and 
        delivery_areas.country='DK' and 
        delivery_areas.active = 1";
        
        $result = DB::select($sql);

  
        if($result){


            $delivery_areas = current($result);

            $delivery_price = $delivery_areas["price"];


        } else {
            

            $msg = Sentence::translate("Unfortunately, we do not deliver in this city. Please choose another delivery type");

            echo json_encode(array("error"=>true,"msg"=>$msg,"url"=>$return_url));
            
            die();


        }
    

    }

?>