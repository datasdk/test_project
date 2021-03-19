<?php

    namespace App\Models\Orders;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use Session;
    use Components;
    

    class Orders extends Model{
        
        
        use HasFactory;


      
        public static function create($option = []){


            $delivery_type = false;
            $payment_type = false;

            $customer_ref_id = 0;

            $return = "key";
            

            $booking_start = time();
            $booking_end = 0;
            $sap = 0;
    
            $comment = false;
            $free_delivery = false;
            $delivery_price = false;
            $promotion_code = false;
            $discount = false;
            $fee = false;
            $delivery_price = false;


            extract($option);

            $create = false;
            
            

            if(empty(Session::get("order_ref_id"))){


                $create = true;


            } else {
                

                $mysqli = DB::mysqli();
              
                //$order_key = Session::get("shop_order_key");

                $order_ref_id = Session::get("order_ref_id");
                

                $sql = "
                select * from orders 
                where 
                id = '".$order_ref_id ."' and 
                order_done = 0";
                

                $order_exists = mysqli_num_rows(mysqli_query($mysqli,$sql));



                if(!$order_exists){ $create = true; }


            }



            if($create){


                $order_key = sha1( time()."_".uniqid() );
                

                // GET LOGGED IN CUSTOMER
                

                if(!$customer_ref_id){


                    $customer_ref_id = Customer::getCustomerId();

                    
                } else {

                    Customer::login_by_id($customer_ref_id);

                }
                

                $customer = Customer::get($customer_ref_id);

                
                // set delivery price, if type is stanard price

                
                $vat_included = Settings::get("vat_included");

                $dt = Settings::get("delivery_type");

                $delivery_standard_price = Settings::get("delivery_standard_price");

                $delivery_price = 0;

                
                if($delivery_type){

                    if($dt == "everywhere"){ $delivery_price = $delivery_standard_price; }

                }
                

                
                // create order
           
              


                $arr1 = 
                array("order_key"=>$order_key,
                      "reference_number"=>uniqid(),
                      "delivery_price"=>$delivery_price,
                      "vat_included"=>$vat_included,
                      "delivery_type"=>$delivery_type,
                      "payment_type"=>$payment_type,

                      );

                


                if($comment){         $arr1["comment"] = $comment; }
                if($free_delivery){   $arr1["free_delivery"] = $free_delivery; }
                if($delivery_price){  $arr1["delivery_price"] = $delivery_price; }
                if($promotion_code){  $arr1["promotion_code"] = $promotion_code; }
                if($discount){        $arr1["discount"] = $discount; }
                if($fee){             $arr1["fee"] = $fee; }
                
                

                
                $arr2 = array();


                if(!empty($customer))
                if(!empty($customer_ref_id)){


                    $arr2 = 
                    array("company"=>$customer["company"],
                          "cvr"=>$customer["cvr"],
                          "firstname"=>$customer["firstname"],
                          "lastname"=>$customer["lastname"],
                          "address"=>$customer["address"],
                          "housenumber"=>$customer["housenumber"],
                          "floor"=>$customer["floor"],
                          "zipcode"=>$customer["zipcode"],
                          "city"=>$customer["city"],
                          "email"=>$customer["email"],
                          "phone"=>$customer["phone"]
                        );
                
                }
                            

                $arr = array_merge($arr1,$arr2);

                $order_ref_id = 
                DB::insert("orders",$arr);


                
                $arr = [
                    "order_ref_id"=>$order_ref_id,
                    "booking_start"=>$booking_start,
                    "booking_end"=>$booking_end,
                    "sap"=>$sap,
                ];
                
                DB::insert("order_booking",$arr);

                

                //Session::set("shop_order_key",$order_key);
                Session::set("order_ref_id",$order_ref_id);
                
            } 
            


            if(isset($arr)){

               Pocket::insert("order_create",$arr); 

            }
            

            return $order_ref_id;


            if($return == "key"){

                return $order_key;

            } else {

                return $order_ref_id;

            }


        }

        

        public static function is_active(){

            if(self::get_order_id()){

                return true;

            }

            return false;

        }


        public static function id(){

            return self::get_order_id();

        }


        public static function get_order_id(){

  

            if(!empty(Session::get("order_ref_id"))){

                
                $mysqli = DB::mysqli();

                $order_ref_id = Session::get("order_ref_id");


                $sql = "
                select * from orders 
                where 
                id = '".$order_ref_id ."' and 
                order_done = 0 
                limit 1";


                $result = mysqli_query($mysqli,$sql);


                if(mysqli_num_rows($result) == 0){ 
                                      
                    self::close();

                    return false; 
                
                }


                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    
                    $id = $row["id"];

                }

                return $id;

            } 

        
            return false;

        }
        


        public static function disconnect(){

           return Session::remove("order_ref_id"); 

        }
        
        


        public static function set_booking($order_ref_id,$booking_start,$booking_end = 0,$sap = 1){



            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }

            if(empty($order_ref_id)){ return false; }



            if(!$booking_start){

                $booking_start = time();

            }



            $sql = "select id from order_booking where order_ref_id = '".$order_ref_id."'";

            $numrows = DB::numrows($sql);
            
        
            // ORDER BOOKING
        

            if($numrows){
        
        
                $sql = "
                update order_booking set
                booking_start = '".$booking_start."',
                sap = '".$sap."'";


                if(!empty($booking_end)){
        
                    $sql.= ",booking_end = '".$booking_end."'";
        
                }


                $sql.= " where order_ref_id = '".$order_ref_id."'";
                
                DB::update($sql);
        
                
                
            } else {
        
        
                $arr = array("order_ref_id"=>$order_ref_id,
                             "booking_start"=>$booking_start,
                             "sap"=>$sap,
                             "booking_end"=>$booking_end
                            );
        
               
                DB::insert("order_booking",$arr);
        
        
            }


        }
      

        public static function get_booking($order_ref_id = 0){


            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
            if(empty($order_ref_id)){ return false; }


            $sql = "
            select * from order_booking 
            where order_ref_id = '".$order_ref_id."'";
        
            $arr = Format::current( DB::select($sql) );
        
            if(empty($arr)){ return false; }
        

            $a = ["booking_start" => $arr["booking_start"], 
                  "booking_end" => $arr["booking_end"] 
                  ];
            
            return $a;


        }
        

        public static function set_delivery($order_ref_id,$delivery_type){



            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            if(empty($order_ref_id)){ return false; }



        
        
            $sql = "
            update orders set
            delivery_type = '".$delivery_type."'
            where 
            id = '".$order_ref_id."'";
                
            DB::update($sql);
        
            

        }


        public static function load_customer($order_ref_id = 0){
            

            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }

            if(empty($order_ref_id)){ return false; }
           

            $sql = "
            select * from orders 
            where id = '".$order_ref_id."'";

            
            $arr = current(DB::select($sql));


     
            $customer["company"]    = $arr["company"];
            $customer["cvr"]        = $arr["cvr"];
            $customer["ean"]        = $arr["ean"];
            $customer["firstname"]  = $arr["firstname"];
            $customer["lastname"]   = $arr["lastname"];
            $customer["address"]    = $arr["address"];
            $customer["housenumber"]= $arr["housenumber"];
            $customer["floor"]      = $arr["floor"];
            $customer["zipcode"]    = $arr["zipcode"];
            $customer["city"]       = $arr["city"];
            $customer["email"]      = $arr["email"];
            $customer["phone"]      = $arr["phone"];
            $customer["comment"]    = $arr["comment"];
            

            return $customer;

        }


        
        public static function get($order_ref_id = 0){
            
  
     
            if($order_ref_id !== "*"){
      
                if(!$order_ref_id){ $order_ref_id = self::get_order_id(); }
           
                if(!$order_ref_id){ return false; }
                
            }
 
           
    
            $mysqli = DB::mysqli();


            $order = array();

            $sql = "
            select *,
            order_specifications.id as specifications_id,
            order_specifications.name as specifications_name,
            order_specifications.value as specifications_value,
            orders.id as order_ref_id

            from orders 

            left join order_booking
            on order_booking.order_ref_id = orders.id

            left join order_booking_persons
            on order_booking_persons.order_ref_id = orders.id

            left join order_specifications
            on order_specifications.order_ref_id = orders.id
            ";


            if($order_ref_id != "*"){

                $sql .= " where orders.id = '".$order_ref_id."' ";

            }
            

           

            $result = mysqli_query($mysqli,$sql);
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                
                $order["invoice_number"] = $row["invoice_number"];
                $order["order_key"] = $row["order_key"];
                $order["formular_ref_id"] = $row["formular_ref_id"];
                $order["subject"] = $row["subject"];
                $order["title"] = $row["title"];
                $order["reference_number"] = $row["reference_number"];
               

                
                $order["order_ref_id"] = $row["order_ref_id"];

                $order["company"] = $row["company"];
                $order["cvr"] = $row["cvr"];
                $order["ean"] = $row["ean"];
                $order["firstname"] = $row["firstname"];
                $order["lastname"] = $row["lastname"];
                $order["address"] = $row["address"];
                $order["housenumber"] = $row["housenumber"];
                $order["floor"] = $row["floor"];
                $order["zipcode"] = $row["zipcode"];
                $order["city"] = $row["city"];
                $order["email"] = $row["email"];
                $order["phone"] = $row["phone"];
                $order["comment"] = $row["comment"];

                $order["free_delivery"] = $row["free_delivery"];
                $order["delivery_price"] = $row["delivery_price"];
                $order["promotion_code"] = $row["promotion_code"];
                $order["discount"] = $row["discount"];
                $order["fee"] = $row["fee"];
                $order["payment_type"] = $row["payment_type"];
                $order["delivery_type"] = $row["delivery_type"];
                $order["date"] = $row["date"];
                $order["payment_date"] = $row["payment_date"];
                $order["order_complete"] = $row["order_complete"];
                $order["order_done"] = $row["order_done"];
                $order["is_paid"] = $row["is_paid"];
                $order["is_new"] = $row["is_new"];
                $order["is_read"] = $row["is_read"];
                $order["removed"] = $row["removed"];
                $order["order_reset"] = $row["order_reset"];
                $order["vat_included"] = $row["vat_included"];
                $order["accepted_terms_of_trade"] = $row["accepted_terms_of_trade"];

                $order["company_ref_id"] = $row["company_ref_id"];
                $order["employee_ref_id"] = $row["employee_ref_id"];
                $order["booking_start"] = $row["booking_start"];
                $order["booking_end"] = $row["booking_end"];
                $order["sap"] = $row["sap"];

                $order["customer_ref_id"] = $row["customer_ref_id"];
                $order["persons"] = $row["persons"];
                $order["count_as_person"] = $row["count_as_person"];

                
                $order["alternative_billing_address"] = self::get_alternative_delivery_address();



                if(!empty($row["specifications_id"])){

                    $order["specifications"][$row["specifications_id"]]["name"]  = $row["specifications_name"];
                    $order["specifications"][$row["specifications_id"]]["value"] = $row["specifications_value"];
                    //$order["specifications"][$row["specifications_id"]]["price"] = $row["specifications_price"];

                }
                
                $all_orders[$row["order_ref_id"]] = $order;

            }
            


            if($order_ref_id === "*"){

                return $all_orders; 

            } else {

               return $order; 

            }            
            

        }


        public static function is_paid($order_ref_id = 0){


            if($order_ref_id){ $order_ref_id = self::get_order_id(); }
            
            if(!$order_ref_id){ return false; }



            $sql = "
            select id from orders 
            where 
            id = '".$order_ref_id."' and
            is_paid = '1'
            ";

            $r = DB::numrows($sql);


            if($r){ return true; }

            return false;


        }


        public static function get_alternative_delivery_address($order_ref_id = 0){


            if(!$order_ref_id){

                if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }

            }


            $sql = "
            select * from orders_second_delivery_address 
            where order_ref_id = '".$order_ref_id."'";

            $result = Format::current( DB::select($sql) );


            if(empty($result)){

                return false;

            }

            return $result;

        }

        public static function set_discount($order_ref_id = 0,$discount,$type="cash"){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }

            if(!$order_ref_id){ return false; }


            $total = self::get_total($order_ref_id);



            if($type == "cash"){

                if($discount > $total){ $discount = $total; }

            } else {

                $discount = ( $total  / 100 * $discount );

            }

            if($discount < 0){ $discount = 0; }
            


            $sql = "update order set discount = '".$discount."' where id = '".$order_ref_id."'";

            DB::update($sql);


            return $discount;


        }


        public static function has_alternative_delivery_address($order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            $sql = "select id from orders_second_delivery_address where order_ref_id = '".$order_ref_id."'";

            
            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }



        public static function set_info($options = []){
            

            $order_ref_id = 0;
            $customer_ref_id = 0;
            $company = false;
            $cvr = false;
            $ean = false;
            $firstname = false;
            $lastname = false;
            $address = false;
            $housenumber = false;
            $floor = false;
            $zipcode  = false;
            $city = false;

            $email = false;
            $phone = false;

            $comment = false;

            $country_code = "dk";

            $update_customer = true;

       
            $payment_type = false;
            $delivery_type = false;
            $booking_start = 0;
            $booking_end = 0;
            $sap = 0;
       


            extract($options);


            if(!$order_ref_id){
    
                if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
    
            }

            if(!$order_ref_id){
                
                return ["success"=>false];

            }

            

            $sql = "
            select id from orders 
            where id = '".$order_ref_id."'";
            
        
            if(DB::numrows($sql)){

                $upd = [];
                
                $sql = "update orders set ";


                if($company){           $upd[] = "company = '".$company."'"; }
                if($cvr){               $upd[] = "cvr = '".$cvr."'"; }
                if($ean){               $upd[] = "ean = '".$ean."'"; }
                if($firstname){         $upd[] = "firstname = '".$firstname."'"; }
                if($lastname){          $upd[] = "lastname = '".$lastname."'"; }
                if($address){           $upd[] = "address = '".$address."'"; }
                if($housenumber){       $upd[] = "housenumber = '".$housenumber."'"; }
                if($floor){             $upd[] = "floor = '".$floor."'"; }
                if($zipcode){           $upd[] = "zipcode = '".$zipcode."'"; }
                if($city){              $upd[] = "city = '".$city."'"; }
                if($email){             $upd[] = "email = '".$email."'"; }
                if($phone){             $upd[] = "phone = '".$phone."'"; }
                if($payment_type){      $upd[] = "payment_type = '".$payment_type."'"; }
                if($delivery_type){     $upd[] = "delivery_type = '".$delivery_type."'"; }
                if($country_code){      $upd[] = "country_code = '".$country_code."'"; }
                
                
                if(isset($is_paid)){    $upd[] = "is_paid = '".$is_paid."'"; }


                if(isset($delivery_price)){    $upd[] = "delivery_price = '".$delivery_price."'"; }
                if(isset($free_delivery)){     $upd[] = "free_delivery = '".$free_delivery."'"; }
                if(isset($vat_included)){      $upd[] = "vat_included = '".$vat_included."'"; }
                if(isset($fee)){               $upd[] = "fee = '".$fee."'"; }
          

                if(isset($customer_ref_id)){    $upd[] = "customer_ref_id = '".$customer_ref_id."'"; }
                if($comment){                   $upd[] = "comment = '".$comment."'"; }
                



                $sql .= implode(",",$upd);
                
                $sql .= " where id = '".$order_ref_id."' ";

                DB::update($sql);
             
                
      
                if($update_customer){
                 
                    Customer::update($options);

                }
                


            } 
      
            
            if($booking_start or $booking_end){

                Order::set_booking($order_ref_id,$booking_start,$booking_end,$sap);

            }
            
         


            return ["success"=>true,"order_ref_id"=>$order_ref_id];

        }


        public static function set_alternative_delivery_address($arr = []){


            $order_ref_id = 0;
            $company = "";
            $cvr = "";
            $ean = "";
            $firstname = "";
            $lastname = "";
            $address = "";
            $housenumber = "";
            $floor = "";
            $zipcode  = "";
            $city = "";
            $shipping_point = "";
            $country_code = "DK";

            extract($arr);


            if(!$order_ref_id){
    
                if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
    
            }


            $sql = "
            select id from orders_second_delivery_address 
            where order_ref_id = '".$order_ref_id."'";
            
        
            if(DB::numrows($sql)){


                
                $sql = "
                update orders_second_delivery_address set

                shipping_point = '".$shipping_point."',
                company = '".$company."',
                cvr = '".$cvr."',
                ean = '".$ean."',
                firstname = '".$firstname."',
                lastname = '".$lastname."',
                address = '".$address."',
                housenumber = '".$housenumber."',
                floor = '".$floor."',
                zipcode = '".$zipcode."',
                city = '".$city."',
                country_code = '".$country_code."'
                
                where order_ref_id = '".$order_ref_id."'
                ";

                DB::update($sql);



            } else {


                $arr = array("shipping_point"=>$shipping_point,
                             "order_ref_id"=>$order_ref_id,
                             "company" => $company,
                             "cvr" => $cvr,
                             "ean" => $ean,
                             "firstname" => $firstname,
                             "lastname" => $lastname,
                             "address" => $address,
                             "housenumber" => $housenumber,
                             "floor" => $floor,
                             "zipcode" => $zipcode,
                             "city" => $city,
                             "country_code"=>$country_code
                            );
                            
                          
                DB::insert("orders_second_delivery_address",$arr);


              
            }

            


        }



        public static function close(){

            Session::remove("order_ref_id");

        }


        public static function load_products($arr = []){
          

            $all = false;
            $only_stock_requests = false;
            $only_picked = false;
            $not_refunded = false;
            $order_ref_id = 0;

            extract($arr);


            $mysqli = DB::mysqli();


            if(!$all){

                if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
                if(empty($order_ref_id)){ return false; }

            }
            

            
            $products = array();


            $sql = "
            select * from order_products             
          
            where 1 
            ";


            if($order_ref_id){

                $sql.= " and order_ref_id = '".$order_ref_id."' ";

            }
            
            
            if($only_stock_requests){

                $sql.= " and stock_request = '1' ";

            }

            if($only_picked){

                $sql.= " and is_picked = '1' ";

            }

            if($not_refunded){

                $sql.= " and is_refunded = '0' ";

            }

            

            $sql.= " order by id";

       

            

            $result = mysqli_query($mysqli,$sql);
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


                $key_id = $row["key_id"];
                $product_id = $row["id"];


                if(isset($products[$key_id])){

                    $products[$key_id]["amount"] += $row["amount"];

                    continue;

                }

                $products[$key_id]["id"] = $row["id"];
                $products[$key_id]["order_ref_id"] = $row["order_ref_id"];
                $products[$key_id]["stock_ref_id"] = $row["stock_ref_id"];
                $products[$key_id]["product_ref_id"] = $row["product_ref_id"];
                $products[$key_id]["category_ref_id"] = $row["category_ref_id"];
                $products[$key_id]["item_number"] = $row["item_number"];
                
                $products[$key_id]["name"] = $row["name"];
                $products[$key_id]["description"] = $row["description"];
                $products[$key_id]["discount"] = $row["discount"];
                $products[$key_id]["price"] = $row["price"];
                $products[$key_id]["timestamp"] = $row["timestamp"];
                $products[$key_id]["locked"] = $row["locked"];
                $products[$key_id]["complete"] = $row["complete"];

                $products[$key_id]["image"] =  $row["image"];


                $products[$key_id]["complete"] = $row["complete"];

                $products[$key_id]["is_returned"] = $row["is_returned"];
                $products[$key_id]["is_delivered"] = $row["is_delivered"];

                
                $products[$key_id]["is_picked"] = $row["is_picked"];
                $products[$key_id]["is_refunded"] = $row["is_refunded"];


                $products[$key_id]["comment"] = $row["comment"];
                $products[$key_id]["active"] = $row["active"];
                $products[$key_id]["amount"] = $row["amount"];



                $v = 
                self::get_order_product_specifikations($row["order_ref_id"],$row["id"]);


                if($v){ $products[$key_id]["variants"] = $v; }
               
                

            }
           
            

            return $products;

        }



        public static function get_order_product_specifikations($order_ref_id = 0,$order_product_ref_id){


            $sql = "
            select * from 
            order_product_specifikations
            where 
            order_ref_id = '".$order_ref_id."' and
            order_product_ref_id = '".$order_product_ref_id."'
            ";


            $res = DB::select($sql);

            if(!$res){ return false; }


            $variants = [];


            foreach($res as $val){

                $variants[$val["category"]]["specification"] = $val["specification"];
                $variants[$val["category"]]["price"] = $val["price"];
                $variants[$val["category"]]["variant_ref_id"] = $val["id"];

            }
                    

            return $variants;


        }
        

        public static function product_exists($order_ref_id,$product_ref_id){


            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
            if(empty($order_ref_id)){ return false; }


            $sql = "
            select id from order_products 
            where 
            order_ref_id = '".$order_ref_id."' and
            product_ref_id = '".$product_ref_id."'
            ";

      
            if(DB::numrows($sql)){

                return true;

            }
            

            return false;

        }


        public static function load_prices($order_ref_id = 0,$filter = false,$hide_completed = 0){


            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
            if(empty($order_ref_id)){ return false; }

            
            
            $prices = array();

            $sum = 0;

            $total_discount = 0;
 


            $sql = "select * from orders where id = '".$order_ref_id."'";

            $result = DB::select($sql);

            
            if(empty($result)){ return false; }


            foreach($result as $row){

                $vat_included   = $row["vat_included"];
                $order_discount = $row["discount"];
                $delivery_price = $row["delivery_price"];
                $fee            = $row["fee"];

                $total_discount += $order_discount;

            }
            



            // PRODUCTS

            $sql = "
            select * from order_products 
            where 
            order_ref_id = '".$order_ref_id."' ";


            if($hide_completed){

                $sql .= " and complete = 0 ";

            }

         
         

            $result = DB::select($sql);


            foreach($result as $row){


                // ignore products with discount
                
                if($filter)
                if($filter == "ignore_products_with_discount")
                if($row["discount"] > 0){ continue; }
                
            
                //$discount = ($row["discount"] * $row["amount"]);

               // $total_discount += $discount;


                $sum += ($row["price"] - $row["discount"]) * $row["amount"];

                $prices[$row["id"]] = $row["price"] * $row["amount"];

            }
       

            $prices["sum"]              = floatval($sum);
            $prices["total_discount"]   = floatval($total_discount);
            $prices["delivery_price"]   = floatval($delivery_price);
            $prices["fee"]              = floatval($fee);


            if($total_discount > $sum){ $total_discount = $sum; }

            $total = ($sum  - $total_discount);

            if($fee > 0){ $total += $fee; }
            else {  $total -= $fee;  }
            
            if($total < 0){ $total = 0; }

            $total += $delivery_price;


            // VAT


            $prices["vat_included"] = floatval($vat_included);
            
            $prices["total"] = floatval($total);


            if(!$vat_included){

                $vat = $total / 100 * 25;

                $subtotal = $total + $vat;
            
            } else {
            
                $vat = $total * 0.2;
                
                $subtotal = $total ;

            }


            $prices["vat"] = floatval($vat);

            $prices["subtotal"] = floatval($subtotal);


            return $prices;


        }


        public static function get_total($order_ref_id = 0){
            
            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
            if(empty($order_ref_id)){ return false; }

            $total = self::load_prices($order_ref_id)["sum"];

            return $total;

        }


        public static function get_subtotal($order_ref_id = 0){


            if(empty($order_ref_id)){ $order_ref_id = self::get_order_id(); }
            if(empty($order_ref_id)){ return false; }


            $total = self::load_prices($order_ref_id)["sum"];

            $order = self::get($order_ref_id);

            $vat_included = $order["vat_included"];

            


            $fee = $order["fee"];

            $delivery_price = $order["delivery_price"];

            $discount = $order["discount"];

  
            $subtotal = ($total + $delivery_price + $fee) - $discount;


            if($subtotal < 0){ $subtotal = 0; }

            $vat = $subtotal / 100 * 25;


            if(!$vat_included){

                return ($subtotal +  $vat);

            }          
            

            return ($subtotal);

        }


        public static function exists($order_ref_id){


            $sql = "select id from orders where id = ".$order_ref_id."";

            $num = DB::numrows($sql);

            if($num > 0){ return true; }

            return false;

        }


        public static function remove_product($order_ref_id = 0,$product_ref_id = 0){
            

            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            if(!$order_ref_id){

                return false;

            }

            
            if(!Order::is_completed($order_ref_id)){


                $sql = "
                delete from order_products 
                where 
                order_ref_id = '".$order_ref_id."' ";


                if($product_ref_id){

                    $sql .= " and product_ref_id = '".$product_ref_id."' ";

                }

                DB::delete($sql);


            }

            

        }


        public static function remove_all_products($order_ref_id = 0){

            self::remove_product($order_ref_id,0);

        }


        public static function add_product($order_ref_id,$product_ref_id,$amount,$variants = array(),$stock_request = 0){
       



            $close_after_closing_time = Settings::get("close_webshop_after_closing_time");

            $enable_shop = Settings::get("enable_shop");     


            
            if(!$enable_shop){

                return (array("success"=>false,"title"=>Language::translate("The shop is not available"),"msg"=>Language::translate("You cannot place an order as the shop is not available")));

            }


            // IF SHOP IS CLOSED AND YOU CANT ORDER AFTER CLOSING TIME

            if(!OpeningHours::is_open())
            if($close_after_closing_time){

                return (array("success"=>false,"title"=>Language::translate("We are closed"),"msg"=>Language::translate("You cannot place an order when we have closed")));

            }

            
           


            $total_amount = 0;


            // variants

            // set product token

            $product_token = sha1($product_ref_id.implode("_",$variants));

            $order_ref_id = Order::get_order_id();



            if(!$order_ref_id){

                $order_ref_id = Order::create();

            } 



            // IF no variants is given, and the variants i required.
            // send back json request, an open up the custom-product-popup

            if(Products::has_variants($product_ref_id) and empty($variants)){

                return ["success"=>false,"open_custom_product_popup"=>true];

            }



            // 

            
            if($amount < 1){ 
                
                return ["success"=>false,"title"=>Language::translate("Select amount"),"msg"=>Language::translate("You cannot add zero products to the cart")]; 
            
            }



            $products = Products::get(["products" => [$product_ref_id] ]);

      

            $order_ref_id = self::get_order_id();

            $language_ref_id = Language::get_default_language();


           

            if(!isset($products[$product_ref_id])){

                return ["success"=>false,"title"=>Language::translate("Product dosent exists"),"msg"=>Language::translate("The product you are trying to add does not exist")];

            }


            $obj = $products[$product_ref_id];

            $item_number = $obj["item_number"];

            $is_book_able = $obj["is_book_able"];

            $category_ref_id = $obj["category_ref_id"];


            $image = $obj["image"];

            $name = $obj["name"];

            $description = $obj["description"];

            $date = $obj["date"];

            $min = $obj["min"];

            $max = $obj["max"];



            $discount = Discount::get($product_ref_id);



            $sku = Stock::get_sku_key($product_ref_id,$variants);

            

            if(!$sku){

                return ["success"=>false,"title" => Language::translate("Variant dosent exists"), "message" => Language::translate("The variant dosent exists")];

            }


            $sql = "
            select amount from 
            order_products 
            where 
            order_ref_id = ".$order_ref_id." and 
            key_id = '".$sku."'";


            $result = DB::select($sql);

            $numrows = DB::numrows($sql);

            $order_amount = intval(current($result)["amount"]);






            // MIN MAX Products

            if(!$numrows)
            if($min)
            if($amount < $min){

                $amount = $min;

            }   



            //sa($amount > $max);


          
           

            if($max)
            if($order_amount + $amount > $max){
            //  echo $order_amount." + ".$amount." > ".$max."<br>";

                $title = Language::translate("Limited");

                $msg = Language::translate("You can only buy a limited amount of this product");
                

                $msg .= " (".$max." ";

                
                if($max == 1){

                    $msg .= Language::translate("product");

                } else {

                    $msg .= Language::translate("products");

                }


                $msg .= ")";

             

                return ["success"=>false,"title"=>$title,"msg"=>$msg];


            }

            // in stock

           
            if($stock_request){

                $in_stock = 1;

            } else {

                $in_stock = Stock::in_stock_by_sku($order_ref_id,$sku,$amount);

            }
            


           


            if(!$in_stock){


                $title = Language::translate("Out of stock");


                if($amount > 1){

                    $msg = Language::translate("The stated quantity of this item is not in stock");;

                } else {

                    $msg = Language::translate("This item or variant is not in stock.");

                }
                
                
                return ["success"=>false,"title"=>$title,"msg"=>$msg];

            }



            // UPDATE


            if($numrows > 0){

                

                $sql = "
                update order_products 
                set 
                amount = amount + ".intval($amount)."
                where 
                order_ref_id = ".$order_ref_id." and 
                key_id = '".$sku."' and 
                complete = 0";
                
                DB::update($sql);


                return ["success"=>true];


            }



            // INSERT

            $stock_ref_id = Stock::get_stock_id($product_ref_id,$variants);


            $q1 = array("key_id"=>$sku,
                        "order_ref_id"=>$order_ref_id,
                        "stock_ref_id"=>$stock_ref_id,
                        "item_number"=>$item_number,
                        "is_book_able"=>$is_book_able,
                        "product_ref_id"=>$product_ref_id,
                        "name"=>$name,
                        "image"=>$image,
                        "timestamp"=>time(),
                        "amount"=>$amount,
                        "stock_request"=>$stock_request,
                        "active"=>1
                        ); 

                $order_product_ref_id = 
                DB::insert("order_products",$q1);
                

                
                // variants

                $all_variants = array();

                $total_price = 0;

                $valuta_ref_id = Valuta::get_default_valuta();


                if(isset($obj["price"])){ 

                    // SINGLE PRICE
                    
                    $total_price = $obj["price"][$valuta_ref_id]["price"]; 
                
                
                }

                else

                if(isset($obj["prices"]["category"]))
                foreach($obj["prices"]["category"] as $category_ref_id => $val){

                    // MULTIBLE PRICE

                    $category = Sentence::get($val["name"]);
                    
                    

                    foreach($variants as $specification_ref_id){


                        if(empty($val["variants"][$specification_ref_id])){ continue; }


                        $val2    = $val["variants"][$specification_ref_id];

                        $specification = Sentence::get($val2["name"]);

                        $price = $val2["price"][$valuta_ref_id];
                        
                        $all_variants[]= $specification;

                        

                        // add price to total price

                            $total_price += $price;


                        // insert to db

                        $q2 = array("order_ref_id"=>$order_ref_id,
                                    "order_product_ref_id"=>$order_product_ref_id,
                                    "category"=>$category,
                                    "specification"=>$specification,
                                    "price"=>$price
                                    ); 
                        


                        DB::insert("order_product_specifikations",$q2);  



                    }

                    
                }



            

                        
            // calculate discount

            $discount_price = 0;

            if($discount){

                $discount_price = ($total_price / 100 * $discount);

            }



            $description = "";

            if($all_variants){

                $description = implode(", ",$all_variants);

            }

            // insert in db

            $sql = "
            update order_products 
            set 
            description = '".$description."', 
            price = '".$total_price."', 
            discount = '".$discount_price."'
            where id = ".$order_product_ref_id;

            DB::update($sql);


           

            $total_amount = 0;

            $sql = "
            select amount from order_products 
            where order_ref_id = '".$order_ref_id."'";
            
            $result = DB::select($sql);



            if(empty($result)){ 
                
                $total_amount = 1; 
            
            }
            
            else

            foreach($result as $val){

                $total_amount += $val["amount"];

            }



            return ["success"=>true,"order_ref_id"=>$order_ref_id,"amount"=>$total_amount];



        }


        public static function update_product($order_ref_id,$product_ref_id,$amount){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
            

            if(!$order_ref_id){ return false; }


            if($amount > 0){

                $sql = "
                update order_products 
                set amount = '".$amount."' 
                where 
                order_ref_id = '".$order_ref_id."' and 
                product_ref_id = '".$product_ref_id."'";

                DB::update($sql);

            } else {

                self::remove_product($order_ref_id,$product_ref_id);

            }
            

        }


       


        public static function set_specification($order_ref_id = 0,$name,$value,$ext = ""){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            $sql = "
            select id from order_specifications 
            where 
            name = '".$name."' and 
            order_ref_id = '".$order_ref_id."'";


            if(DB::numrows($sql)){


                $sql = "
                update order_specifications set
                value = '".$value."',
                ext = '".$ext."'
                where 
                order_ref_id = '".$order_ref_id."' and 
                name = '".$name."'";

                DB::update($sql);


            } else {


                $arr = 
                array(
                    "order_ref_id" => $order_ref_id, 
                    "name" => $name, 
                    "value" => $value,
                    "ext" => $ext
                );


                $id = DB::insert("order_specifications",$arr);


                
            }
                        

        }



        public static function get_specification($order_ref_id = 0,$name = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            $sql = "
            select * from order_specifications 
            where 
            order_ref_id = '".$order_ref_id."' ";


            if($name){

                $sql .= " and name = '".$name."' ";

            }
            
            $sql .= " order by sorting";


            $r = DB::select($sql);


            if(empty($r)){ return false; }


            if($name){

                $r = Format::current($r);

                $r = $r["value"];

            }


            return $r;


        }



        public static function remove_specification($order_ref_id = 0, $name = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            if(!$order_ref_id){ return false; }


            $sql = "
            delete from order_specifications 
            where 
            order_ref_id = '".$order_ref_id."' ";
            

            if($name){

                $sql .= " and name = '".$name."' ";

            }
            
            
            return DB::delete($sql);
                    

        }


        public static function remove_all_specification($order_ref_id = 0){

            self::remove_specification($order_ref_id, 0);

        }

        
        public static function set_persons($order_ref_id,$product_ref_id,$persons){

         

            if(empty($persons)){ return false; }

           

            $r = Booking::products($product_ref_id);  
            
            
            if(self::exists($order_ref_id))
            if($r){


                $sql = "select id from order_booking_persons where order_ref_id = '".$order_ref_id."' ";


                if(DB::numrows($sql)){


                    $sql = "
                    update order_booking_persons set
                    persons = '".$persons."'
                    where 
                    order_ref_id = '".$order_ref_id."' and
                    product_ref_id = '".$product_ref_id."'
                    ";

                    DB::update($sql);


                } else {


                    $arr = array("order_ref_id"=>$order_ref_id,
                                 "product_ref_id"=>$product_ref_id,             
                                 "persons"=>$persons,
                                 "children"=>0,
                                 "count_as_person"=>1,
                                );
                    
                    DB::insert("order_booking_persons",$arr);

                }

            
            }
            

            return false;

        }



        public static function get_customer_by_order_id($order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }

            if(!$order_ref_id){

                return false;

            }


            $o = Order::get($order_ref_id);
            
            $customer_ref_id = $o["customer_ref_id"];

            if(!empty($customer_ref_id)){

                return $customer_ref_id;

            }

            return false;
           

        }

        

        public static function remove_all_specifications($order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }


            $sql = "
            delete from order_specifications 
            where order_ref_id = '".$order_ref_id."'";
            
            DB::delete($sql);
        

        }


        public static function copy($options = []){


            $order = [];
            $update_existing_order = true;
            $close_current_order = false;
            $empty_order = true;
            $order_ref_id = 0;
            $update_if_exists = 1;

            extract($options);

           
            
            if(!isset($order["order_ref_id"])){ return false; }


         
         
            if($close_current_order){

                self::close();

            }
            

            if($update_existing_order){


                $update_if_exists = 0;


                if(!$order_ref_id){

                    $order_ref_id = self::get_order_id();

                }


                Cart::empty();


                if(!$order_ref_id){ 

                    $order_ref_id = self::create($order);

                }


            } 


            //order 

            
            $order["order_ref_id"] = $order_ref_id;

            self::set_info($order);
            


            if(isset($order["products"])){ 


                $products = $order["products"];

   
                foreach($products as $key_id => $val){
         
            
                    $val["order_ref_id"] = $order_ref_id;
                    $val["update_if_exists"] = $update_if_exists;
    
    
                    self::add_custom_product($val);
                        
                }
    

                // booking

                $booking_start = $order["booking_start"];
                $booking_end   = $order["booking_end"];
                $sap           = $order["sap"];
    
    
                self::set_booking($order_ref_id,$booking_start,$booking_end,$sap);


            }



            return $order_ref_id;
            

        }



        public static function complete($arr = []){

            $order_ref_id = 0;
            $customer_ref_id = 0;
            $accept_newsletter = 0;
            $create_account = 0;
            $password = "[RANDOM]";
            $payment_type = 0;
            $delivery_type = 0;
            $send_customer_mail = 1;
            $send_admin_mail = 1;
            $attach_files = 1;
            $formular_ref_id = 0;
            $attach_appendix = 1;
            $attach_terms = 1;
            $stock_request = 0;


            $company = false;
            $cvr = false;
            $ean = false;

            $firstname = false;
            $lastname = false;
            $address = false;
            $housenumber = false;
            $floor = false;
            $zipcode = false;
            $city = false;
            $phone = false;
            $email = false;
            $comment = false;

            $subscription = false;
            $card_type = "";

            $ccrg = false;
            
            $error = false;

            extract($arr);

           
      

            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }
            
     

            if(empty($order_ref_id)){

                return ["success"=>false,"msg"=>"no order avariable"];

            }
        
 

            if($customer_ref_id == "none"){

                $customer_ref_id = 0;

            } else {

                if(!$customer_ref_id){

                    $customer_ref_id = Customer::getCustomerId();
    
                }

            }
        


            $order = Order::get($order_ref_id);
            

            if($order)
            if($order["order_done"]){

                $r = [
                    "success"=>false,
                    "msg"=>"Order is alreay completed"
                ];

                return $r;

            }
      


            include(__DIR__."/include/customer_update.php");
           
                 
            include(__DIR__."/include/stock.php");


            include(__DIR__."/include/booking.php");


            include(__DIR__."/include/invoice.php");
           

            include(__DIR__."/include/email.php");
       
            
            // pas på med smartifart.

            include(__DIR__."/include/payment.php");
         
   

            if($error){

                
                $r = [
                    "success"=>false,
                    "msg"=>$error
                ];

                return $r;

            }



            include(__DIR__."/include/order.php");


            


            Pocket::insert("complete-order");


            $r = [
                "success"=>true,
                "customer_ref_id"=>$customer_ref_id,
                "order_ref_id"=>$order_ref_id
            ];

            
            return $r;
            
        }


        public static function get_customer_from_order($order_ref_id = 0){

            
            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }

            
            if(empty($order_ref_id)){

                return false;

            }

         

            $c = Order::get($order_ref_id);


            if(isset($c["customer_ref_id"])){

                return $c["customer_ref_id"];

            }

            return false;

        }

        public static function add_customer_to_order($customer_ref_id,$order_ref_id = 0){


            if(!$order_ref_id){

                $order_ref_id = self::get_order_id();

            }

            
            if(empty($order_ref_id)){

                return false;

            }


   

            $c = Customer::get($customer_ref_id);


                $sql  = "
                update orders set 
                customer_ref_id = '".$c["id"]."',
                firstname = '".$c["firstname"]."',
                lastname = '".$c["lastname"]."',
                address = '".$c["address"]."',
                housenumber = '".$c["housenumber"]."',
                floor = '".$c["floor"]."',
                zipcode = '".$c["zipcode"]."',
                city = '".$c["city"]."',
                email = '".$c["email"]."',
                phone = '".$c["phone"]."'
                where id = '".$order_ref_id."' ";

                DB::update($sql);


        }


        public static function add_comment($order_ref_id,$message){


            if(self::exists($order_ref_id)){

                if(!self::is_completed($order_ref_id)){


                    $sql = "update orders set comment = '".$message."' where id = '".$order_ref_id."'";

                    DB::update($sql);


                }

            }

            return false;

        }



        public static function is_completed($order_ref_id){

            if(self::exists($order_ref_id)){

                $sql = "select id from orders where id = '".$order_ref_id."' and order_done = 1";

                if(DB::numrows($sql)){

                    return true;

                }

            }
            
            return false;
        }


        public static function add_to_cart($order_ref_id,$product_ref_id,$amount = 1,$variants = []){



            $close_after_closing_time = Settings::get("close_webshop_after_closing_time");

            $enable_shop = Settings::get("enable_shop");     
            
            
            // 
            
            if(!$enable_shop){
            
                return ["success" => false, "title"=>Language::translate("The shop is not available"),"msg"=>Language::translate("You cannot place an order as the shop is not available") ];
            
                exit();
            
            }
            
            
            // IF SHOP IS CLOSED AND YOU CANT ORDER AFTER CLOSING TIME
            
            if(!OpeningHours::is_open())
            if($close_after_closing_time){
            
                return [ "success" => false, "title" => Language::translate("We are closed"),"msg"=>Language::translate("You cannot place an order when we have closed") ];
            
                exit();
            
            }
            
            
            
                    
            $total_amount = 0;
            
            
            // variants
         
            
            
            // set product token
            
            $product_token = sha1($product_ref_id.implode("_",$variants));
            
            $order_ref_id = Order::get_order_id();
            
            
            
            if(!$order_ref_id){
            
                $order_ref_id = Order::create();
            
            } 
            
            
            
            // IF no variants is given, and the variants i required.
            // send back json request, an open up the custom-product-popup
            
            if(Layout::get("shoplist") == "menu")
            if(Products::has_variants($product_ref_id) and empty($variants)){
            
                $arr = [ "success" => true, 
                         "product_ref_id" => $product_ref_id, 
                         "open_custom_product_popup" => true 
                        ];

                return $arr;
             
                exit();
            
            }
            
            
           
            $result = Order::add_product($order_ref_id,$product_ref_id,$amount,$variants );
            
            $result = json_decode($result,1);;
            
            
            
            
            if(isset($result["success"]))
            if($result["success"] == false){
                
                return $result;
                
            
            }
            
            
            
            $total_amount = 0;
            
            $sql = "select amount from order_products 
            where order_ref_id = '".$order_ref_id."'";
            
            $result = DB::select($sql);
            
            
            
            if(empty($result)){ 
                
                $total_amount = 1; 
            
            }
            
            else
            
            foreach($result as $val){
            
                $total_amount += $val["amount"];
            
            }
            

            
            
            
            return [ "success" => true, "amount" => $total_amount ];
            
        
            

        }


        public static function add_subject($order_ref_id,$subject){


            if(self::exists($order_ref_id)){

                if(!self::is_completed($order_ref_id)){


                    $sql = "update orders set subject = '".$subject."' where id = '".$order_ref_id."'";

                    DB::update($sql);


                }

            }

            return false;


        }
        

        public static function set_payment_type($order_ref_id,$payment_type){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }

            if(!$order_ref_id){ return false; }
            
            

            if(self::exists($order_ref_id)){


                if(!self::is_completed($order_ref_id)){


                    $sql = "
                    update orders 
                    set payment_type = '".$payment_type."' 
                    where id = '".$order_ref_id."'";

                    DB::update($sql);


                }


            }


            return false;


        }


        public static function quick_order($options = []){


            $coupon_code  = false;
            $product_ref_id = false;
            $amount = 1;

            $firstname = false;
            $lastname = false;
            $email = false;
            $password = false;

            $payment_type = false;

            $payment = false;

            $cardno = false;
            $expyear = false;
            $expmonth = false;
            $cvc = false;
            
            $cardholder = false;
            $stock_request = false;

            $variants = [];
            

            $company = false;
            $cvr = false;
            $ean = false;
            $firstname = false;
            $lastname = false;
            $address = false;
            $housenumber = false;
            $floor = false;
            $zipcode = false;
            $city = false;
            $phone = false;
            $email = false;
            $password = false;
            $products = false;
            



            $accept_newsletter = false;
            $create_account = false;
            $send_customer_mail = true;
            $send_admin_mail = true;
            $attach_files = true;
            
            $attach_appendix = true;
            $attach_terms = true;
            $stock_request = false;


            
            extract($options);
            

            if(
                !$amount
            ){

             
                $title = "Error";

                $msg = "Der er ikke valgt noget product";
                
                return array("success"=>false,"title"=>$title,"msg"=>$msg) ;
              
            }

    

            Cart::empty();

            $order_ref_id = Order::create($options);

        
        


            $products = [];

            if(isset($options["products"])){

                $products = $options["products"];

            } 
            
/*
            if($product_ref_id){

                $products[] = 
                [
                "product_ref_id" => $product_ref_id, 
                "amount" => $amount
                ];

            }
      */
          

  
            if(is_array($products))
            foreach($products as $val){

                if(
                empty($val["product_ref_id"]) or 
                empty($val["amount"])){ continue; }
                

                $product_ref_id = $val["product_ref_id"];
                $amount = $val["amount"];

               

                $variants = [];

                if(isset($val["variants"])){

                    $variants = $val["variants"];

                }
        


                $params = [
                    "order_ref_id" => $order_ref_id,
                    "product_ref_id" => $product_ref_id,
                    "amount" => $amount,
                    "variants" => $variants
                ];



                $pm = Products::add_to_cart($params);
                
              
             

                if(!is_array($pm)){ $pm = json_decode($pm,1);}

         

                if(isset($pm["success"]))
                if($pm["success"] == false){

                    return array("success"=>false,"title"=>$pm["title"],"msg"=>$pm["msg"]);

                }

            }
            
            

       
            // cartholder
        
        
            $payment_complete = false;
        
            $cardholder = false;
        

            if($payment)
            if(!$cardno or !$expyear or !$expmonth or !$cvc or !$cardholder){


                 $r = Payment::create_payment($cardno,$expyear,$expmonth,$cvc,$cardholder);
        
          
                if($r["success"] == 1){
            

                    $payment_complete = true;    

                    $payment_type = "stripe";
                    
                    
                } else {
                       

                    $title  = $r["title"];
                    $msg    = $r["msg"];
            
                    return array("success"=>false,"title"=>$title,"msg"=>$msg) ;
                          
            
                }


            }
                
           
          
         
           
            if($payment_complete or !$payment){


                $customer_ref_id = Customer::add($company,$cvr,$ean,$firstname,$lastname,$address,$housenumber,$floor,$zipcode,$city,$phone,$email,$password);
        
            
                
        
                Order::add_customer_to_order($customer_ref_id,$order_ref_id);
        

                if(!empty($coupon_code)){

                    Promotion::set($coupon_code,$order_ref_id);

                }
                
                
                $arr = ["payment_type"=>$payment_type,
                        "customer_ref_id"=>$customer_ref_id,
                        "stock_request"=>$stock_request,
                        "accept_newsletter"=>$accept_newsletter,
                        "create_account"=>$create_account,
                        "send_customer_mail"=>$send_customer_mail,
                        "send_admin_mail"=>$send_admin_mail,
                        "attach_files"=>$attach_files,
                        "attach_appendix"=>$attach_appendix,
                        "attach_terms"=>$attach_terms,
                        "stock_request"=>$stock_request
                    ];


                    



                Order::complete($arr);
        
        

               
                return 
                array(
                    "success"=>true,
                    "title"=>"Bestilling er gennemført",
                    "msg"=>"Vi har nu modtaget din bestilling",
                    "customer_ref_id"=>$customer_ref_id,
                    "order_ref_id"=>$order_ref_id
                    ) ;
        
                     
        
            } 
                    
        

        }


        public static function custom_product_exists($order_ref_id = 0, $name){


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }

            if(!$order_ref_id){

                return false;

            }


            $key_id = sha1("custom_product_".$name);


            $sql = "
            select id from order_products 
            where 
            key_id = '".$key_id."' and order_ref_id = '".$order_ref_id."'";


            if(DB::numrows($sql)){

                return true;

            }

           
            return false;

        }

        
        public static function add_test_product(){

            
            $options = [
                "amount"=>1,
                "name"=>"Test product",
                "description"=>"Test description",
                "price"=>123
            ];
             
            self::add_custom_product($options);

        }


        public static function add_custom_product($options = []){


            $order_ref_id = false;
            $amount = 1;
            $name = false;
            $description = false;
            $image = false;
            $discount = false;
            $price = false;
            $active = 1;
            $update_if_exists = true;
            $complete = false;

           

            extract($options);


            $image = Image::get_main_image($image);


            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }

            
            if(!$order_ref_id){ 

                $params = $options;
                $params["return"] = "id";


                $order_ref_id = self::create($params);

            }
            


            if(!$order_ref_id){ return false; }
            if(!$name){ return false; }
            if(!$amount){ return false; }
            if(!$price){ return false; }
      

        
            $key_id = sha1("custom_product_".$name);


            if(self::custom_product_exists($order_ref_id,$name) and $update_if_exists){

       

                $upd = [];

                $sql = "
                update order_products
                set 
                ";


                $upd[] = "amount = '".$amount."'";

                $upd[] = "price = '".$price."'";


                if($description){   $upd[] = "description = '".$description."'"; }
                
                if($image){         $upd[] = "image = '".$image."'"; }

                if($discount){      $upd[] = "discount = '".$discount."'"; }

                if($complete){      $upd[] = "complete = '".$complete."'"; }

                
                $sql.= implode($upd,",");
                

                $sql.= "
                where 

                order_ref_id = '".$order_ref_id."' and
                key_id = '".$key_id."'
                ";

              

                return DB::update($sql);



            } else {


                
                

                $arr = [
                    "order_ref_id"=>$order_ref_id,
                    "key_id"=>$key_id,
                    "amount"=>$amount,
                    "name"=>$name,
                    "description"=>$description,
                    "image"=>$image,
                    "discount"=>$discount,
                    "price"=>$price,
                    "complete"=>$complete,
                    "active"=>$active
                ];

           

                return DB::insert("order_products",$arr);

            }


        }



        public static function reopen($order_ref_id){


            
            if(self::exists($order_ref_id)){


               // if(self::is_completed($order_ref_id)){


                    $sql = "
                    update orders set 
                    order_done = 0 
                    where 
                    id = '".$order_ref_id."'";

                    DB::update($sql);


                    Session::set("order_ref_id",$order_ref_id);

                    
                    return $order_ref_id;


                //}

            }


            return false;

        }


    }

?>





