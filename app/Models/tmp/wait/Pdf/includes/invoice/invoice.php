<?php


    $company    = Company::get();
    $order      = Order::get($order_ref_id);
    $products   = Order::load_products(["order_ref_id"=>$order_ref_id]);

 

    $delivery_type  = $order["delivery_type"];
    $payment_type   = $order["payment_type"];
    $booking_start  = $order["booking_start"];
    $booking_end    = $order["booking_end"];
    $sap            = $order["sap"];

    

    $delivery_price = $order["delivery_price"];
    $fee = $order["fee"];
    $discount = $order["discount"];

    $vat_included = $order["vat_included"];
    $vat_registered = Settings::get("vat_registered");


    

    if(!empty($company["phone"])){

        foreach($company["phone"] as $val){

            if($val["main_number"] and $val["number"]){

                $company["phone"] = "Tlf: ".$val["number"];

                break;

            }

        }
        
    }



    if(!empty($company["email"])){

        foreach($company["email"] as $val){

            if($val["transactions"]){

                $company["email"] = $val["email"];

                break;

            }

        }

    }



    if(!empty($company["cvr"])){    $company["cvr"]   = "Cvr: ".$company["cvr"]; }
    //if(!empty($company["phone"])){  $company["phone"] = "Tlf: ".$company["phone"]; }

    if(!empty($order["cvr"])){   $order["cvr"] = "Cvr: ".$order["cvr"]; }
    //if(!empty($order["phone"])){ $order["phone"] = "Tlf: ".$order["phone"]; }



    use Konekt\PdfInvoice\InvoicePrinter;

    $invoice = new InvoicePrinter("a4","kr.","dk");


    $logo = Logo::insert(false,false,false);
 
   // $logo = file_get_contents($logo);
 
    if(!empty($logo)){

        $invoice->setLogo($logo);   //logo image path

    }   
       

    


    $title = Sentence::translate("Receipt");

    if(!$order["is_paid"] and !$order["order_complete"]){

        $title = Sentence::translate("Invoice");

    }



    $invoice->setTimeZone('Europe/Copenhagen');
    $invoice->setColor("#333");      // pdf color scheme
    $invoice->setType($title);    // Invoice Type
    

    $time = date("d/m/y H:i",$order["booking_start"]);

    if($order["booking_end"]){

        $time .= " - ".date("d/m/y H:i",$order["booking_end"]);

    }


    if(!empty($payment_type)){

         $invoice->setHeader(Sentence::translate("Payment type"),Payment::translate($payment_type));

    }
   
    
    if(!empty($delivery_type)){


        $invoice->setHeader(Sentence::translate("Delivery type"),Delivery::translate($delivery_type));


        $label = "Time";

        if($booking_start and $booking_end){


            if($delivery_type == "booking"){

                $label = "Period";

            } else {

                $label = "Time interval";

            }
            
        }


        if(!$sap and $time) {
                       
            $invoice->setHeader(Sentence::translate($label),$time);
        
        } 
        
        else 
        
        {
    
            $invoice->setHeader(Sentence::translate($label),Sentence::translate("As soon as possible"));
    
        } 
        

    }
     


    $invoice->setHeader(Sentence::translate("Invoice number"),$order["invoice_number"]);



     // IS PAID

     if($order["is_paid"]){

        $paidtxt = Sentence::translate("Is paid");

    } else {

        $paidtxt = Sentence::translate("Not paid");

    }

 

    
    //$invoice->setNumberFormat(0, ".");

    //$invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date

    
    $customer_company = "";


    if($order["company"]){ $customer_company = $order["company"]." / ".$order["cvr"]; }

    $arr = array($company["company"],
                 $company["company"]." / ".$company["cvr"],
                 $company["address"],
                 $company["zipcode"]." ".$company["city"]);

    $invoice->setTo($arr);
    
    
    $att = $order["firstname"]." ".$order["lastname"];
    $com = $customer_company;
    $addr = $order["address"]." ".$order["housenumber"]." ".$order["floor"];
    $city = $order["zipcode"]." ".$order["city"];


    if(!empty($com)){ 

        $line1 = $att;
        $line2 = $com;
        $line3 = $addr;
        $line4 = $city;

    } else {

        $line1 = $att;
        $line2 = $addr;
        $line3 = $city;
        $line4 = "";

     }
    


    $invoice->setFrom(array($line1,$line2,$line3,$line4));



    $total = 0;

    
    if(!empty($products))
    foreach($products as $val){


        $name = $val["name"];
        $description = preg_replace( "/\r|\n/", "", substr($val["description"],0,50) );
        $amount = $val["amount"];


        $price = $val["price"];

        
        $single_price = $amount * $price;

        $total += $single_price;


        if(strlen($val["description"]) > 50){ $description .= "..."; }


        $invoice->addItem($name,$description,$amount,false,$price,false,$single_price);

    }



    $subtotal = $total + $delivery_price + $fee - $discount;


    $invoice->addTotal(Sentence::translate("Total"),$total);
    


    if($delivery_price > 0){

        $invoice->addTotal(Sentence::translate("Delivery"),$delivery_price);

    }
    

    if($fee > 0){

        $invoice->addTotal(Sentence::translate("Adm. fee"),$fee);

    }
    

    if($discount > 0){

        $invoice->addTotal(Sentence::translate("Discount"),"-".$discount);
        
    }
    



    if($vat_included and $vat_registered){


        $total_vat = ($subtotal * 0.2);

        $subtotal_incl_vat = ($subtotal);


    } else {
        
        
        if($vat_registered){

            $total_vat = ($subtotal / 100 * 25);

        } else {

            $total_vat = 0;

        }
        

        $subtotal_incl_vat = ($subtotal + $total_vat);


        if($vat_registered){

            $invoice->addTotal(Sentence::translate("Vat"),$total_vat);

        }
        
        
    }


    $txt = "Subtotal";

    if($vat_registered){

        $txt .= " incl. VAT";

    }


    $invoice->addTotal( Sentence::translate($txt),$subtotal_incl_vat );


    if($vat_registered)
    if($vat_included){

        $invoice->addTotal(Sentence::translate("VAT constitutes"),$total_vat);

    }


   

    

    if(Order::has_alternative_delivery_address($order_ref_id)){

        $al = Order::get_alternative_delivery_address($order_ref_id);

        $invoice->addTitle("Leveres til:");
        
        $text = 
        $al["company"]."<br>".
        $al["firstname"]." ".$al["lastname"]."<br>".
        $al["address"]." ".$al["housenumber"]." ".$al["floor"]."<br>".
        $al["zipcode"]." ".$al["city"];

        $invoice->addParagraph($text);



    }
    


    // BANK

    if($payment_type == "bank"){


        $invoice->addTitle("Bank oplysninger");


        $bank = Company::bank();


        $iso = $bank["iso"];
        $control_key = $bank["control_key"];
        $registration = $bank["registration"];
        $account_no = $bank["account_no"];

        $text = "Beløbet overføres til konto: \n\nReg nr.: ".$registration."\nKonto nr.: ".$account_no;

        $invoice->addParagraph($text);

    }
    

    $s = Order::get_specification($order_ref_id);


    if($s){


        $text = Sentence::translate("Note")."\n\n";

        foreach($s as $val){

            $text .= $val["name"].":  ".$val["value"]." ".$val["ext"]."\n";


        }
        

        $invoice->addParagraph($text);


    }


    //$invoice->setFooternote($company["company"]." / ".$company["cvr"]." / ".$company["phone"]." / E-mail: ".$company["email"]);

    $path = 'assets/pdf/'.uniqid().".pdf";

    $invoice->render($path,'F');
    
    
   

    /* I => Display on browser, D => Force Download, F => local path save, S => return document path */

?>