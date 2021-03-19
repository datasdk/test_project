<?php    
      

  /*

    if($subscription){
              


        if(!$ccrg){

            $ccrg = Customer::get_ccrg($customer_ref_id);

        } 

     

        if($ccrg){

            $card_hint = "";
            

            Customer::set_ccrg($customer_ref_id,$ccrg,$card_hint,$card_type);
            

            if(empty($ccrg)){

                $error = "ccrg is empty";

            }
            else {

                
                $w = Yourpay::withdraw($order_ref_id);

             
                if(!$w["success"]){

                    $error = $w["msg"];
        
                }

            }
            


        }
        

    }


    */
    