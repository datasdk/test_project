<?php

    namespace App\Models\Discount\Discount;

    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    class Discount extends Model{


        public static $discount = array();


        public static function load(){
            

            $mysqli = DB::mysqli();

            $discount = array();

            $beginning_of_day = strtotime("midnight");


            $sql = "
            select *,
            discount.id as discount_id,
            discount_products.discount as product_discount
            from discount
        
            left join discount_products
            on discount.id = discount_products.discount_ref_id
            
            where
            
            (
            discount.always_variable = 1 or 
            (".$beginning_of_day." >= discount.start_time and ".$beginning_of_day." < discount.end_time)
            )

            and 
            discount.active = 1
            
            order by discount.name 

            ";
        
     
        
            $result = mysqli_query($mysqli,$sql);
        
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        
        
                $discount[$row["discount_id"]]["name"] = ucfirst($row["name"]);
                $discount[$row["discount_id"]]["always_variable"] = $row["always_variable"];
                $discount[$row["discount_id"]]["start_time"] = $row["start_time"];
                $discount[$row["discount_id"]]["end_time"]  = $row["end_time"];
                $discount[$row["discount_id"]]["active"]    = $row["active"];
        
    
                $discount[$row["discount_id"]]["products"][$row["product_ref_id"]] = $row["product_discount"];
        
        
            }


            self::$discount = $discount;

        }

    


      

        public static function get($product_ref_id){


            if(empty(self::$discount)){ self::load(); }

            $discount = self::$discount;


            foreach($discount as $discount_id => $val){


                $now = time();

                $obj = $discount[$discount_id]["products"];
                

                if(isset($obj[$product_ref_id])){

                    return $obj[$product_ref_id];

                }
            
                
            }


            return false;

        }


    }

   
?>