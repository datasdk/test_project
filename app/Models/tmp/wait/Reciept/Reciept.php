<?php

    namespace App\Models\Api\Api;

    class Reciept{


        public static function get($order_ref_id = 0,$type = false,$hide_completed=0){

            
            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();


                if(empty($order_ref_id)){
    
                    $order_ref_id = Session::get("last_order_ref_id");
    
                }    

            }
            

            if(empty($order_ref_id)){

                return false;

            }
     
          

            $prices = Order::load_prices($order_ref_id,false,$hide_completed);


            $sql = "select * from orders where id = '".$order_ref_id."'";

            $order = current(DB::select($sql));
            

            $vat_included = $order["vat_included"];
            $vat_registered = Settings::get("vat_registered");

            $delivery_price = $order["delivery_price"];


            $sum            = $prices["sum"];
            $discount       = $prices["total_discount"];
            $delivery_price = $prices["delivery_price"];
            $fee            = $prices["fee"];
            $vat_included   = $prices["vat_included"];
            $vat            = $prices["vat"];
            $total          = $prices["total"];
            $subtotal       = $prices["subtotal"];

         

            

            $calculation = array();


            // amount
            if($sum)
            $calculation[] = 
            array(
            "name"=>Sentence::translate("Purchase"),
            "value"=>Format::number($sum,1)." kr.",
            "bold"=>false,
            "subtotal"=>false
            );

            
            // admin gebyr

            if($fee)
            $calculation[] = 
            array(
            "name"=>Sentence::translate("Admin fee"),
            "value"=>Format::number($fee,1)." kr.",
            "bold"=>false,
            "subtotal"=>false
            );


            // rabat

            if($discount)
            $calculation[] = 
            array(
            "name"=>Sentence::translate("Discount"),
            "value"=>"-".Format::number($discount,1)." kr.",
            "bold"=>false,
            "subtotal"=>false
            );



            // delivery

          //  if($order["delivery_type"] == "delivery"){


                if($order["free_delivery"]){

                    $str_delivery_price = Sentence::translate("Free");
                
                } else {

                    $str_delivery_price = Format::number($delivery_price,1)." kr.";
                    
                }

                // always show delivery when its free.
                // dont show if its not free and not is set jet

                $show_delivery = true;

                if(!$order["free_delivery"] and empty($delivery_price)){ $show_delivery = false; }
                
                
                if($str_delivery_price)
                if($show_delivery)
                $calculation[] = 
                array(
                "name"=>Sentence::translate("Delivery"),
                "value"=>$str_delivery_price,
                "bold"=>false,
                "subtotal"=>false
                );

          //  }


      

            // total 
            


            if(!$vat_included){

                
                $calculation[] = 
                array(
                "name"=>Sentence::translate("Total"),
                "value"=>Format::number($total,1)." kr.",
                "bold"=>true,
                "subtotal"=>false
                );


                if($vat_registered)
                if($vat){

                    $calculation[] = 
                    array(
                    "name"=>Sentence::translate("Vat"),
                    "value"=>Format::number($vat,1)." kr.",
                    "bold"=>false,
                    "subtotal"=>false
                    );

                }
                


            } else {

               // $calculation[] = array("name"=>Sentence::translate("Total"),"value"=>Format::number($total,1)." kr.","bold"=>true);
    
            }





            // vis ikke for type checkout
          

                $txt = "Subtotal";

                if($vat_registered){

                    $txt .= " incl. VAT";

                }

                $calculation[] = 
                array(
                "name"=>Sentence::translate($txt),
                "value"=>Format::number($subtotal,1)." kr.",
                "bold"=>true,
                "subtotal"=>true
                );

         


            if($vat_registered)
            if($vat_included){

                $calculation[] = 
                array(
                "name"=>Sentence::translate("VAT constitutes"),
                "value"=>Format::number($vat,1)." kr.",
                "bold"=>false);
                
    
            }
        
            

            return $calculation;

        }


        

        public static function create($order_ref_id = 0){


            $images_in_shop = Layout::get("images_in_shop");

            $order_ref_id = intval($order_ref_id);
            

            if(!$order_ref_id){

                $order_ref_id = Order::get_order_id();

            }

            
            ob_start();

          

            if($order_ref_id){

                include(__DIR__."/includes/header.php");

                $products = Order::load_products(["order_ref_id"=>$order_ref_id]);

            }
            


            if(empty($products)){

               // echo "<div class='alert alert-danger block'>Ordren findes ikke i arkivet</div>";    
            
                return false;

            }

            else 
                    
            {

               
                echo "<div class='reciept'>";


                include(__DIR__."/includes/sender.php");


                include(__DIR__."/includes/product.php");
                
                    


                        $s = Order::get_specification($order_ref_id);


                        if(0)
                        if($s){

                            echo '<div class="order_specifications">';


                            echo '<div class="specifications-header">'.Sentence::translate("Note").'</div>';



                                foreach($s as $val){


                                    echo '<div class="specifications-item">';
                                        
                                        echo '<div class="name">';
                                            
                                            echo $val["name"];

                                        echo '</div>';

                                        echo '<div class="price">';

                                            echo $val["value"]." ".$val["ext"];

                                        echo '</div>';

                                    echo '</div>';


                                }
                            
                        
                            echo "</div>";

                        }
                    
               
                    
                    echo "<div class='calculation'>";


                        self::set_calculation(["order_ref_id"=>$order_ref_id]);
                    

                    echo "</div>";

  
                  
                
                }



                echo "</div>";
                
                $con = ob_get_contents();

                ob_get_clean();


                return $con;

            }
            


        public static function set_calculation($option = []){
            

            $order_ref_id = 0;
            $type = false;
            $hide_completed = 0;

            extract($option);


            $calculation = self::get($order_ref_id,$type,$hide_completed);
    
      

           // subtotal
            if(!empty($calculation))
            foreach($calculation as $arr){

                
                $class = "";

                if(!empty($arr["bold"])){ $class = "bold"; }
                if(!empty($arr["subtotal"])){ $class .= " subtotal"; }
                
                
                echo '<div class="specification">';


                    echo '<div class="name '.$class.'">';


                        if($arr["bold"]){ echo "<strong>"; }

                        echo ucfirst($arr["name"]);

                        if($arr["bold"]){ echo "</strong>"; }
                        
                        
                    echo '</div>';


                  


                    echo '<div class="price '.$class.'">';


                        if($arr["bold"]){ echo "<strong>"; }

                        echo $arr["value"];

                        if($arr["bold"]){ echo "</strong>"; }
                    

                    echo '</div>';
        
                    
                echo '</div>';


            }

        }


        public static function list($arr){


            echo "<div class='reciept_list'>";
            
            foreach($arr as $key => $val){}
            
            echo "<d>";

            echo "<div class='reciept_col'>At betale:</div>";

            echo "<div class='reciept_col'>".$subtotal."</div>";
            
            echo "</div>";


        }


    }

?>