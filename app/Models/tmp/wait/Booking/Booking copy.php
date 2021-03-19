<?php

    namespace App\Models\Booking\Booking;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    Class Booking extends Model{

        

     


        public static function is_available($options = []){


            $product_ref_id = 0;
            $booking_start = 0;
            $booking_end = 0;
            $company_ref_id = 0;
            $persons = 0;

            extract($options);



            if(!$product_ref_id){ return false; }


            if($booking_end)
            if($booking_start > $booking_end){ return false; }


            $customer_ref_id = 0;
            $order_ref_id = 0;
   

            $book = self::get($product_ref_id, $customer_ref_id, $order_ref_id, $booking_start, $booking_end);


            $p = Format::current($book["product"]);

   

            $orders = $book["orders"];

            $now = time();

           


            $order_ref_id = Order::get_order_id();

            

            if(!OpeningHours::is_open($company_ref_id, $booking_start)){
              
            //    return false;

            } 
           

            
            if(!$p["active"]){

                return false;

            }
            
        
/**
*  HAS MIN PERIOD
*/

            
            $has_min_period = $p["has_min_period"];


            if(!$booking_end)
            if($has_min_period){


                $booking_end = $booking_start; 



                $days = $p["min_periode_days"];
    
                $minutes = $p["min_periode_days_minutes"];

                $timestamp = strtotime("midnight + ".( $days )." days ",$now);

                $timestamp = strtotime("+ ".( $minutes )." minutes",$timestamp);


               // echo date("d/m Y H:i",$booking_end)." < ".date("d/m Y H:i",$timestamp)."<br>";


                if( $booking_end < $timestamp ){

                    $booking_end = $timestamp;

                 //   echo " SKIFT TIL : ".date("d/m Y H:i",$booking_end)."";

                }


            }

            
/**
*  HAS MAX PERIOD
*/      



            $has_max_period = $p["has_max_period"];

          
           
            if($booking_end)
            if($has_max_period){



                $days = $p["max_periode_days"];
    
                $minutes = $p["max_periode_days_minutes"];

                $timestamp = strtotime("midnight + ".( $days )." days ",$now);

                $timestamp = strtotime("+ ".( $minutes )." minutes",$timestamp);


              //  echo date("d/m Y H:i",$booking_end)." > ".date("d/m Y H:i",$timestamp)."<br>";


                if( $booking_end > $timestamp ){

                    $booking_end = $timestamp;

                  //  echo " SKIFT TIL : ".date("d/m Y H:i",$booking_end)."";

                }


            }


     

 /**
*  COUNT BOOKINGS
*/           
       
            $total_bookings = 0;
 
            

            if(isset($orders[$product_ref_id])){

             
    

                foreach($orders[$product_ref_id] as $order_id => $val){

                
                    if($order_ref_id == $order_id){ continue; }
                   
                    if($val["removed"]){ continue; }
                  
    
                    $bs     = $val["booking_start"];
                    $be     = $val["booking_end"];
    
                   // if(empty($be)){ $be = $bs; }
                        
    
                  // echo da($booking_start)." >= ".da($bs)." and ".da($booking_start)." <= ".da($be)."<br>";

                  // echo da($booking_end)." > ".da($bs)." and ".da($booking_end)." < ".da($be)."<br>";
           
                  // echo da($bs)." >= ".da($booking_start)." and ".da($be)." < ".da($booking_end)."<br>";

                 //   echo date("d/m Y H:i",$bs)." - ".date("d/m Y H:i",$be)."<br>";
    
    
                   // if($p["limited_bookings"]){
    
    
                    if(
                    ($booking_start >= $bs AND $booking_start < $be) 
                    OR 
                    ($booking_end > $bs AND $booking_end <= $be) 
                    OR
                    ($bs >= $booking_start AND $be <= $booking_end) 
                    ){
                             
                      
                        $total_bookings += 1;
            
                    } 
                    
    
                }


            } else {

                return true;

            }
            
        
    
    
    // MAX BOOKINGS


    $ap =  false;


    if(isset($book["product"][0])){

        $ap =  $book["product"][0];

    }


    // ap = all_products / standard settings
    if($ap){
        

        $booking_type = $ap["booking_type"];

        

        if($booking_type == "rent_booking"){

            $max_bookings_pr_interval = 1;

        } else {

            $max_bookings_pr_interval = $ap["max_bookings_pr_interval"];

        }


      
        if($max_bookings_pr_interval){

            //sa($total_bookings." >= ".$max_bookings_pr_interval);

            if($total_bookings >= $max_bookings_pr_interval){

                return false;

            }

        }

    }
    

/**
*  PRE ORDER
*/

            $preorder = $p["preorder"];
            $min_preorder_days = $p["min_preorder_days"];
            $min_preorder_days_minutes = $p["min_preorder_days_minutes"];

       
            if($preorder){
              

                $days = $min_preorder_days;

                $minutes = $min_preorder_days_minutes;


                $timestamp = strtotime("midnight + ".( $days )." days ",$now);

                $timestamp = strtotime("+ ".( $minutes )." minutes",$timestamp);



                if($booking_start < $timestamp){
                               
                    return false;

                }


            }

   



/**
*  VARIABLE DATES
*/


           $always_avariable = $p["always_avariable"];

           $start_date = $p["start_date"];
           $end_date = strtotime("+ 1 day -1 second",$p["end_date"]);



           if(!$always_avariable){

                if($now < $start_date){ return false; }
                if($now > $end_date){ return false; }

           }

           
            return true;


        }



        public static function has_booking($product_ref_id){


            $booking_active = Settings::get("booking_active");

            if(!$booking_active){ return false; }



            $sql = "select id from products where id = '".$product_ref_id."' and is_book_able = 1";


            if(DB::numrows($sql)){

                return true;

            }

            
            return false;

        }



        public static function insert($arr = []){  


            $product_ref_id = 0;
            $category_ref_id = 0;
            $submit_button = true;
            $variants = [];
            $type = "formular";
            $time_type = "blocks";
            $formular_name = "booking_default";
            $auto_update = true;
            $default_timestamp_start = time();
            $default_timestamp_end = time();


            extract($arr);

        

            if(empty($product_ref_id)){


                $sql = "select id from products where is_book_able = 1 limit 1";

                $result = Format::current( DB::select($sql) );

                if($result){

                    $product_ref_id = $result["id"];

                } else {

                    $success = false;

                }

            }


            $booking_active = Settings::get("booking_active");

         
            

            $success = true;


            if(!$booking_active){ 
                
                $success = false;

            }


            $p = self::products($product_ref_id);

            
            $order_ref_id = Order::id();

            $o = Order::get($order_ref_id);

         
            




            if(!$success){


                $txt = Sentence::translate("Booking is not available in the shop");

                echo "<div class='alert alert-danger'>".$txt."</div>";

                return false;


            }



            if($type == "formular"){


                include(__DIR__."/includes/templates/formular.php");


            }



            if($type == "shop"){
                

                include(__DIR__."/includes/templates/shop.php");


            }


            
            if($type == "specifications"){
             
                
                include(__DIR__."/includes/templates/specifications.php");


            }
            
            
        }

        

        public static function get_booking_settings($product_ref_id = 0){


            $arr = self::get($product_ref_id);


            if(isset($arr["product"][0])){

                $result = $arr["product"][0];

            }


            
            foreach($arr["product"] as $val){

                if($val["id"] == $product_ref_id){

                    $result = $val;

                }

            }  



            if(empty($result)){ return false; }


            return $result;


        }



        public static function insert_person_option($product_ref_id = 0){



            $r = self::get_booking_settings($product_ref_id);

                
            $persons_is_relevant        = $r["persons_is_relevant"];
            $min_persons_pr_interval    = $r["min_persons_pr_interval"];
            $max_persons_pr_interval    = $r["max_persons_pr_interval"];
            $max_persons                = $r["max_persons"];
        

            if(!$persons_is_relevant){ return false; }

            

            ob_start();



                echo "<select name='persons' class='persons'>";



                    for($i=0; $i<$max_persons; $i++){


                        $person  = Sentence::translate("person");
                        $persons = Sentence::translate("persons");

                        if($min_persons_pr_interval > $i){ continue; }
                        if($max_persons_pr_interval < $i){ break; }

                        if($i == 1){ $label = $person; }
                        else { $label = $persons; }


                        echo "<option value='".$i."'>".$i." ".$label."</div>";


                    }
                    

                echo "</select>";



                $content = ob_end_clean();


                ob_end_flush();



            return $content;
  

        }


        public static function insert_product_option($product_ref_id = 0,$variants = 0){


            $p = self::products($product_ref_id);
         

            if(empty($p)){ return false; }




            ob_start();
        

                if(!$product_ref_id){


                    echo "<select name='product_ref_id' class='booking_events'>";


                        foreach($p as $val){


                            echo "<option value='".$val["id"]."'";
                            

                            if($product_ref_id == $val["id"]){ echo " selected "; }


                            echo ">".$val["name"]."</option>";
                            

                        }


                    echo "</select>";



                } else {

                    

                    $p = Format::current($p);


                    echo "<div class='booking-settings'>";

                        echo "<h2>".$p["name"]."</h2>";

                        echo "<p>".$p["description"]."</p>";

                    echo "</div>";


                }



                $content = ob_end_clean();


            ob_end_flush();



            return $content;
            
            
        }



        public static function insert_contact_formular($name,$product_ref_id = 0){



            if($product_ref_id){

                $p = Format::current( self::products($product_ref_id));
                
                $name = $p["name"];

            }
            

            return Formular::insert(["name"=>$name],false);


        }



        public static function insert_date_option($product_ref_id = 0,$persons = 0){

            

            $arr = ["name"=>"booking_datepicker",
                    "type"=>"booking",
                    "default_timestamp"=>0,
                    "product_ref_id"=>$product_ref_id,
                    "persons"=>$persons
                    ];

 
            Datepicker::insert($arr);



        }



        public static function get_total_days($from_timestamp, $to_timestamp,$count_current_date = true){
            
            

            $from_timestamp = strtotime("midnight",$from_timestamp);

            $to_timestamp = strtotime("midnight",$to_timestamp);


            $total_days = round(($to_timestamp - $from_timestamp) / 86400);


            if($count_current_date){

                $total_days ++;

            }


            return $total_days;


        }




        public static function room_for_persons($product_ref_id,$timestamp,$persons){
            

            $r = self::get_booking_settings($product_ref_id);

            $di = Delivery::load("booking");

           
            $interval_minutes = Delivery::get_time_interval("booking");


            $min_persons_pr_interval = $r["min_persons_pr_interval"];
            $max_persons_pr_interval = $r["max_persons_pr_interval"];
            $max_persons             = $r["max_persons"];
         
        

            $in = $di[date("N",$timestamp)];

            $from_interval = strtotime( $in["from"] , $timestamp);

            $to_interval = strtotime( $in["to"] ,$timestamp);


            // GET BOOKINGS


            $orders = false;


            $sql = "
            select * from order_booking_persons 
            
            inner join order_booking
            on order_booking.order_ref_id = order_booking_persons.order_ref_id
            
            where 
            order_booking_persons.product_ref_id = '".$product_ref_id."' and

            order_booking.booking_start >= '".strtotime("midnight",$timestamp)."' and

            order_booking.booking_start <= '".strtotime("midnight +1 day -1 second",$timestamp)."'
            ";


            
            foreach(DB::select($sql) as $val){


                $orders[$val["order_ref_id"]]["persons"] = $val["persons"];
                $orders[$val["order_ref_id"]]["booking_start"] = $val["booking_start"];


            }


            

            $total_persons = array();


            if($orders)
            for($i = $from_interval ; $i <= $to_interval ; $i += ( $interval_minutes * 60)){


                $from = $i;
                
                $to = $i + $interval_minutes * 60;

               // echo date("d/m Y H:i",$from)." - ".date("d/m Y H:i",$to)."<br>";

                if(!isset($total_persons[$from])){ $total_persons[$from] = 0; }


                foreach($orders as $order_ref_id => $val){

                    
                   // echo date("d/m Y H:i",$val["booking_start"])." >= ".date("d/m Y H:i",$from)."<br>";

                  //  echo date("d/m Y H:i",$val["booking_start"])." < ".date("d/m Y H:i",$to)."<br>";


                    if($val["booking_start"] >= $from and $val["booking_start"] < $to){

                        $total_persons[$from] += $val["persons"];

                    }

                }

            }



            
            
            //echo "<strong>".date("d/m Y H:i",$timestamp)."</strong>";
            if( isset($total_persons[$timestamp]) ){
                

                if( ( $total_persons[$timestamp] + $persons )  > $max_persons_pr_interval ){

                    return false;

                }

            }
            


            return true;

        }


      



        public static function datepicker($category_ref_id = 0){


            

        }



        public static function products($product_ref_id = []){  
            

            if($product_ref_id)
            if(!is_array($product_ref_id)){ $product_ref_id = [$product_ref_id]; }


            $sql = "select id from products where is_book_able = 1 ";
            

            if(!empty($product_ref_id)){

                $sql .= "and ( id = '".implode("' or id = '",$product_ref_id)."' ) ";

            }

       

            $p = DB::select($sql);

            $p = array_map(function($val){ return $val["id"]; },$p);


            $result = Products::get(["products" => $p ]);

            if(empty($result)){ return false; }

            return $result;
        

        }



       


        public static function get_information($order_ref_id,$language_ref_id = 0){


            $o = Order::get($order_ref_id);
        

            $firstname      = $o["firstname"];
            $booking_start  = $o["booking_start"];
            $booking_end    = $o["booking_end"];
            $delivery_type  = $o["delivery_type"];
            $persons        = $o["persons"];
            $comment        = $o["comment"];
            $subject        = $o["subject"];


            $dt = "d/m Y - H:i";



            $specifications = false;

            if(isset($o["specifications"])){

                $specifications = $o["specifications"];

            }

            
            $information = "";

    
            if(!empty($subject)){

                $information .= "<div><strong>".$subject."</strong></div>";

            }
            


            $information .= "<strong>".Sentence::translate("Arrival",$language_ref_id).": ".date($dt ,$booking_start)."</strong><br>";



            if($booking_end){

                $information .= "<strong>".Sentence::translate("Departure",$language_ref_id).": ".date($dt ,$booking_end)."</strong><br>";

            } 


            if(!empty($persons)){

                $information .= "".Sentence::translate("Persons",$language_ref_id).": ".$persons." ".Sentence::translate("PCS.",$language_ref_id)."<br>";

            }

            
            if(!empty($comment)){

                $information .= "".Sentence::translate("Comment",$language_ref_id).": <i>".$comment."</i><br>";

            }


     

            return $information;

        }


    }

?>