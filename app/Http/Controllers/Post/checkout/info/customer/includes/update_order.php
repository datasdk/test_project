<?php


// DESTINATION 

     $des1 = $_POST; 

     $sqlARR = array();


     if(!empty($customer_ref_id)){

          $sqlARR[]= "customer_ref_id = '".$customer_ref_id."'";

     }
     


     if(isset($des1["organization"])){

          $sqlARR[]= "company = '".ucfirst($des1["organization"])."'";
          $sqlARR[]= "cvr = '".ucfirst($des1["cvr"])."'";

     }


     $sqlARR[]= "firstname = '".ucfirst($des1["firstname"])."'";
     $sqlARR[]= "lastname = '".ucfirst($des1["lastname"])."'";


     if(isset($des1["address"])){


          $sqlARR[] = "address = '".ucfirst($des1["address"])."'";
       

          if(isset($des1["housenumber"])){

               $sqlARR[] = "housenumber = '".ucfirst($des1["housenumber"])."'";
               $sqlARR[] = "floor = '".ucfirst($des1["floor"])."'";

          }
          

          $sqlARR[]  = "zipcode = '".ucfirst($des1["zipcode"])."'";
          $sqlARR[]  = "city = '".ucfirst($des1["city"])."'";

     }
     

     $sqlARR[]  = "email = '".ucfirst($email)."'";

     $sqlARR[]  = "phone = '".ucfirst($phone)."'";


     $sqlARR[]  = "comment = '".ucfirst($comment)."'";


     // delivery
     $sqlARR[]  = "delivery_price = '".($delivery_price)."'";


     // UPDATE ORDER

     $sql = "update orders set ".implode(",",$sqlARR)." where id = '".$order_ref_id."'";



     DB::update($sql);




     // BILLING DESTINATION

     
     if(!Shipping::is_package_store($delivery_type))
     if($another_delivery_address)
     if(isset($destination[1])){



          $des2 = $destination[1]; 


          $insARR = array();

          $insARR["order_ref_id"] = $order_ref_id;

          
          if(isset($des2["organization"])){

               $insARR["company"] = ucfirst($des2["organization"]);
               $insARR["cvr"] = ucfirst($des2["cvr"]);

          }


          $insARR["firstname"] = ucfirst($des2["firstname"]);
          $insARR["lastname"] = ucfirst($des2["lastname"]);


          if(isset($des2["address"])){


               $insARR["address"] = ucfirst($des2["address"]);
          

               if(isset($des2["housenumber"])){

                    $insARR["housenumber"] = ucfirst($des2["housenumber"]);
                    $insARR["floor"] = ucfirst($des2["floor"]);

               }
               

               $insARR["zipcode"] =  ucfirst($des2["zipcode"]);
               $insARR["city"] = ucfirst($des2["city"]);

          }
          

        
          $arr = ["order_ref_id"=>$order_ref_id,
                  "company" => $insARR["company"],
                  "cvr" => $insARR["cvr"],
                  "firstname" => $insARR["firstname"],
                  "lastname" => $insARR["lastname"],
                  "address" => $insARR["address"],
                  "housenumber" => $insARR["housenumber"],
                  "floor" => $insARR["floor"],
                  "zipcode" => $insARR["zipcode"],
                  "city" => $insARR["city"]
                 ];


          Order::set_alternative_delivery_address($arr);

          
     }

     

     // FINISH

     

   

?>