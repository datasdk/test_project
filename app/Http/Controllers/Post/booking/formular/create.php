<?php
    
    $booking_datepicker = false;
    
    $amount = 1;
    
    $persons = 0;

    $variants = [];

    $product_ref_id = 0;

 

    $organisation = "";
    $ean = "";
    $cvr = "";
    $firstname = "";
    $lastname = "";
    $address = "";
    $housenumber = "";
    $floor = "";
    $zipcode = "";
    $city = "";
    $email = "";
    $phone = "";
    $comment = "";
    $formular_ref_id = 0;
    $subject = "";

    $country_code = "DK";


    extract($_POST);



   


    $variant_needed = false;

    $pv = Products::get_variants( $product_ref_id );   


    if(!empty($pv)){


        foreach($pv as $category_ref_id => $val1){

            
            if(!isset($variants[$category_ref_id])){
                 

                echo json_encode(["success" => false , "title" => ucfirst(Sentence::translate("Select variant")) , "message" => ucfirst(Sentence::translate("One or more variants are not selected")) ]);

                exit();

    
            }
    
        }

    }
        

       



    if($booking_datepicker)
    if(empty($booking_datepicker["date"]) or empty($booking_datepicker["time"])){


        echo json_encode(["success" => false , "title" => ucfirst(Sentence::translate("Reminder")) , "message" => ucfirst(Sentence::translate("Date and time is not defined")) ]);

        exit();

    } 



    if(!reCaptsha::validate($_POST)){


        echo json_encode(array("success"=>false,"title"=>Sentence::translate("reCaptsha is invalid"),"msg"=>Sentence::translate("Please confirm that you are not a robot")));


        exit();

    } 



    $d = $booking_datepicker;
    $timestamp = $d["timestamp"];

    
        
    $customer_ref_id = Customer::get_customer_id_by_email($email);


        // if customer not exists
    if(!$customer_ref_id){


        $customer_ref_id = 
        Customer::add($organisation,$cvr,$ean,$firstname,$lastname,$address,$housenumber,$floor,$zipcode,$city,$phone,$email);


    }
        
    

    $order_ref_id = Order::create(["customer_ref_id"=>$customer_ref_id,"return"=>"id"]);


    $r = 
    Order::add_to_cart($order_ref_id,$product_ref_id,1,$variants);


    if(!$r["success"]){


        echo json_encode(["success" => false , "title" => ucfirst($r["title"]) , "message" => ucfirst($r["msg"]) ]);


        die();

    }


    
    $p = Format::current( Products::get( [ "products" => $product_ref_id ] ) );

    if($p){ $subject = $p["name"]; }

    
    
    Order::set_booking($order_ref_id,$timestamp,0,0);


    Order::set_persons($order_ref_id,$product_ref_id,$persons);


    Order::add_comment($order_ref_id,$comment);


    Order::add_subject($order_ref_id,$subject);




    // INSERT ADDREASS

    $arr = ["company" => $organisation,
            "cvr" => $cvr,
            "ean" => $ean,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "address" => $address,
            "housenumber" => $housenumber,
            "floor" => $floor,
            "zipcode" => $zipcode,
            "city" => $city,
            "country_code" => $country_code
           ];

    Order::set_info($arr);



    // COMPLETE

    $arr = [
        "order_ref_id"=>$order_ref_id,
        "customer_ref_id"=>$customer_ref_id,
        "create_account"=>true,
        "payment_type"=>"arrive",
        "delivery_type"=>"booking",
        "attach_appendix" => 0,
        "formular_ref_id" => $formular_ref_id

    ];


    Order::complete($arr);





    $form = Formular::get_formular_by_token($token);

    

    $reciept_title = $form["reciept_title"];

    $reciept_message = $form["reciept_message"];


    if(empty($reciept_title)){

        $reciept_title = Sentence::translate("Thank you for your inquiry");

    }

    if(empty($reciept_message)){

        $reciept_message = Sentence::translate("We strive to return as soon as possible.");

    }


    echo json_encode(["success" => true , "title" => ucfirst($reciept_title) , "message" => ucfirst($reciept_message) ]);



    exit();
     

?>