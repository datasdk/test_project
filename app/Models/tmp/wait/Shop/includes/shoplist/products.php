<?php


   
    if(is_array($products))
    foreach($products as $product_ref_id => $arr3){


        
        if(!$arr3["active"]){ continue; }
        

        $languages_ref_id = Language::get_default_language();

        $pick_able = $arr3["pick_able"];
        
        $item_number = $arr3["item_number"];

        $id = $arr3["id"];
        

        $name = $arr3["name"];

       // $resume = $arr3["resume"];

        $description = $arr3["description"];

        
   
        
 


        $date = $arr3["date"];
        $single_price = true;

        
        

        // STOCK

        $in_stock = 1;

        $stock = Stock::get($product_ref_id);


        if(isset($stock["in_stock"])){

            $in_stock = $stock["in_stock"];

        }   
       
        
        
        // FILTER
        
      
        if($only_show_empty_stock and $in_stock){
            
            continue;

        } else if(!$in_stock or $only_show_empty_stock){

            continue;

        }
        
        

        if($layout == "webshop"){

            $order_url = $arr3["url"];

        } 
        




        
        $min_price = 0;

        $prices = array();

        $category_path = array();
        
        $has_variants = false;

        $from = "";
        
        $class = "";


        // The groupe discount will be replaced with product discount if active


        $discount = Discount::get($product_ref_id);

       

        // PRICES


        $all_prices = array();

        $variant_array = array();

        $all_variants = array();

        $has_variants = false;

        $valuta_ref_id = Valuta::get_default_valuta();

        $valuta_code = Valuta::get_valuta_code();


        if(isset($arr3["prices"])){


            $variant_array = $arr3["prices"]["category"];

            

            foreach($variant_array as $variant_category_ref_id => $arr4){
    

                foreach($arr4["variants"] as $specification_ref_id => $arr5){
                    
                    
                    $has_variants = true;

                    $all_prices[$variant_category_ref_id][$specification_ref_id] = $arr5["price"][$valuta_ref_id];

                    $price = $arr5["price"][$valuta_ref_id];
                    
                    $all_variants[$specification_ref_id] = array("name" => $arr5["name"], "price" => $price);
    
    
                }

                
            }


        } else {
            
            $all_variants[0] = array("name" => "", "price" => $arr3["price"][$valuta_ref_id]["price"]);

        }
        

        
        // MULTI PRICE

        if($has_variants){ 
            

            $has_variants = true;

            $from = "<span class='from'>fra</span>";


            foreach($all_prices as $arr6){

                $min_price += min($arr6);

            }


        } else {

            // SINGLE PRICE

            if(isset($arr3["price"])){ 
            
                $min_price = $arr3["price"][$valuta_ref_id]["price"]; 
        
                $single_price = false;
                
            }

        }


  

        if($discount){ 
            

            $before_price = $min_price;

            $min_price -= round($min_price / 100 * $discount);

            $min_price = $min_price;

            $before_price = $before_price;


        } else {

            $min_price = $min_price;

        }


        $min_price = str_replace(".00","",$min_price);
        
        $shop_image_height = intval(Settings::get("shop_image_type"));




        
        if(!$has_product_prevew){

            $order_url = "javascript:add_to_cart(".$product_ref_id.")";;;

        }

        if($has_variants){
              
            // har varianter?? hvad var den til??
           // $order_url = $url;

        }

        
        if(!$has_product_prevew){
     
            $order_url = $cart_url;

        }
        

        if($custom_cart_function){

            $order_url = "javascript:".$custom_cart_function."(".$product_ref_id.")";

        }



        $class = "";

        if($highligth_products)
        if(in_array($product_ref_id,$highligth_products)){

            $class = $highligth_class;

        }



    

        echo "<li 
        class='
        product 
        product_".$product_ref_id." 
        ".$class."'
        data-product_ref_id='".$product_ref_id."'
        >";


            echo "<div class='product-content type-".$shop_image_height."'>";

            
                echo "<div class='img '>";

              

                    $image = null;


                    if(isset($arr3["images"])){

                        $image = current($arr3["images"]);
                 
                    }


            

                    if($layout == "webshop"){

                        
                        if(!empty($order_url)){

                            echo "<a href='".$order_url."' >"; 

                        }
                        
                   
                        
                            if($image){
    

                                echo "<div class='image' style='background-image: url(".$image."); '></div>";
        

                            } else {
        

                                echo "<img src='/assets/images/interface/no_image.jpg'>";
        

                            }
                            

                            if(!empty($order_url)){

                                echo "</a>";

                            }
                        

                    }
                    

                echo "</div>";



                echo "<div class='description'>";

                
                    if($layout == "webshop"){ echo "<a href='".$order_url."'>"; }
                    

                        echo "<div class='text'>";


                            echo "<strong class='product_title'>".ucfirst($name)."</strong>";
                            
                            if(isset($description))
                            if($layout == "menu"){

                                echo "<div class='resume'>".ucfirst(nl2br($description))."</div>";

                            }
                        

                        echo "</div>";
                        
                    
                    if($layout == "webshop"){ echo "</a>"; }
                    

                    
                    echo "<div class='price-wrapper'>";
                            

                        if($discount){
                                
                            //  echo "<div class='before-price'><strike>".$before_price."</strike></div>";

                        }

                            
                        // list all prices if layout is menu and shop is not enabled
             
                
                        if($layout == "menu" and !$layout_shop_cart){
                                
                             

                            foreach($all_variants as $val2){

                                $price = $val2["price"];

                                if($price < 0){ continue; }

                                if($discount){ 
            
                                    $price -= round($price / 100 * $discount);

                                }


                                if(!Price::is_free($price)){

                                    echo "<div class='after-price block'>";

                                        echo "<div class='price'>".Price::insert($price)."</div>";
                                    
                                        echo "<div class='name'>".ucfirst(Sentence::get($val2["name"]))."</div>";
                                    
                                    echo "</div>";

                                }
                                

                            }
                    

                        } else {

                            echo "<div class='after-price'>".$from." ".Price::insert($min_price)."</div>";

                        }


                    
                    echo "</div>";

                
                echo "</div>";
                    

                // DISCOUNT

         

                    $bagde = "";
        
                    if($discount){  $bagde = "-".$discount." %"; }
        
                    if(!$in_stock){ $bagde = "Udsolgt"; }
                    
                    
                    echo "<span class='badge badge-danger'>".$bagde."</span>";



                    echo "<div class='bottom-bar'>";


                        //if($available)
                        if($pick_able)
                        if($layout_shop_cart)
                        if($enable_shop)
                        if($in_stock){


/*

sæt denne på efter du har ryttet op i shoplist
                            echo "<div class='add_to_cart_wrapper'>";

                       
                                if($has_amount){

                                    echo "<input type='number' value='1' class='add_to_cart_number add_to_cart_number_".$product_ref_id."'>";

                                }
                            

                                echo "<a href='".$order_url."' class='order-now add_to_cart add_to_cart_".$product_ref_id."'>";


                                    echo "<span class='loader'><img src='/assets/images/interface/spinner_4.gif'></span>";


                                    if($layout == "webshop"){

                                        echo "
                                        <!--googleoff: index-->
                                        <span class='text'>
                                        ";


                                            
                                            
                                            if($custom_cart_text){

                                                echo $custom_cart_text;

                                            } else {
                                                
                                                echo "<i class='fas fa-shopping-cart'></i>";
                                                
                                                echo Sentence::translate("Add to cart"); 

                                            }
                                            

                                    
                                        echo 
                                        "</span>
                                        <!--googleon: index-->
                                        "; 

                                    } 

                            
                                    if($layout == "menu"){
                                        
                                        
                                        echo "<span class='text'><i class='fas fa-plus'></i></span>";

                                        
                                    }
                                    
                                
                                echo "</a>";


                            echo "</div>";

                            */

                            
                        }

                        


                echo "</div>";

  

            echo "</div>";
                        
            

        echo "</li>";


       
    }

            
?>