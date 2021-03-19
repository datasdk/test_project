<?php



    include(__DIR__."/includes/header.php"); 

    include(__DIR__."/includes/customer_login_validate.php");


    // hvis kunden IKKE er logged ind og e-mail eksisterer 
    


    if($success){
        
   

        if(Shipping::is_package_store($delivery_type)){
      
        
            $shipping_point = $_POST["shipping_point"];

            Shipping::insert_alternative_delivery_address_by_shipping_point($shipping_point,"gls");
       
        }
        
        else 
        
        {

            
            $delivery_method = Settings::get("delivery_type");


            if($delivery_method == "zipcode"){
    
                include(__DIR__."/includes/delivery_price_zipcode.php"); 

            }
    
            else
    
            if($delivery_method == "radius"){
                
                include(__DIR__."/includes/delivery_price_radius.php"); 
        
            }
    
            else 
            
            if($delivery_method == "everywhere"){
    
                include(__DIR__."/includes/delivery_price_everywhere.php"); 
                
            }


        }


     

        include(__DIR__."/includes/free_delivery.php"); 
       
        include(__DIR__."/includes/update_order.php"); 
        
        include(__DIR__."/includes/customer.php"); 
        
        include(__DIR__."/includes/newsletter.php");

     
        
    } else {

        echo json_encode( array("error"=>true,"msg"=>$msg,"url"=>$return_url) );

        exit();

    }



    

?>