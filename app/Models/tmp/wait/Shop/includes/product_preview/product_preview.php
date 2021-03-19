<?php


    $sub_url = "";

    $variants_url = "";

    $selected_variants = array();



    if(!$product_ref_id){

        $product_ref_id = Shop::get_product_by_url();

    }



    if($product_ref_id){

        $product =  Products::get(["products" => [$product_ref_id]]);

    }
    

    $enable_shop = Settings::get("enable_shop");    

    $vat_included = Settings::get("vat_included");    

    $has_booking = Booking::has_booking($product_ref_id);



    if(empty($product)){


        echo "<div class='msg msg-danger'>".Sentence::translate("This item / service is not available")."</div>";


    }

    else 

    {

?>


<?php

    if($has_form){

        echo "<form method='post' onSubmit='return product_submit_form(this)'>";

    }

?>          



    <div class="product_preview <?php echo $layout;?>">



        <?php

            
            $language_ref_id = Language::get_default_language();

            $valuta_ref_id = Valuta::get();

    

            foreach($product as $product_ref_id => $val){

                
                
                $name = $val["name"];
               
                $description = nl2br($val["description"]);


                $item_number = $val["item_number"];
                $category_ref_id = $val["category_ref_id"];
                $date = $val["date"];

                
                $in_stock = 1;

                $stock_amount = false;
                

                $stock = Stock::get($product_ref_id);

                if(isset($stock["in_stock"])){

                    $in_stock = $stock["in_stock"];
                    
                    $stock_amount = current($stock["stock"])["amount"];
                
                }
                
                

                
                if(!$description){

                   // $description = Sentence::translate("Beskrivelse kommer snart");

                }


                $categories = Categories::get(["categories" => [$category_ref_id]] );
                
            
          
                $discount  = Discount::get($product_ref_id);


        

                
                $price = 0;
                $before_price = 0;

                $valuta_ref_id = Valuta::get_default_valuta();


                if(isset($val["price"])){  $price = $val["price"][$valuta_ref_id]["price"]; }
                

             
                if($discount){

                    $before_price = $price - round($price / 100 * $discount);

                }
            


                if($has_image){

                    include(__DIR__."/includes/images.php");
                
                }

               
            
                

                echo "<div class='info'>";
                
                    
                    if($discount){

                    //    echo "<span class='badge badge-danger'>-".$discount." %</span>";   

                    }

                    echo "<h1>";
                    
                        echo ucfirst($name);

                    echo "</h1>";



                    if($accessorie_ref_id){

                        Shop::insert(["type"=>"table","category_ref_id"=>$accessorie_ref_id]);
                      
                    }
                    
                
        
                    echo "<div class='price-wrapper'>";

                        if($before_price){

                            echo "<div class='before-price'>";
                            
                                echo Sentence::translate("BEFORE")." kr. ".Format::number($before_price,true); 
                            
                            echo "</div>";

                        }
                            
                        echo "<div class='after-price sum'>";
                            
                            if($price){ echo "kr. ".Format::number($price,true); }
                            
                        echo "</div>";


                        echo "<div class='vat'>";

                            if($vat_included){
                                    
                                 echo Sentence::translate("INCL. VAT");
                                    
                            } else {

                                echo Sentence::translate("EXCL. VAT");

                            }

                        echo "</div>";

                    
                    echo "</div>";

                                
                    // booking
                        

                                  


                    echo "<input type='hidden' class='standard_price' value='".$price."' readonly>"; 

                    echo "<input type='hidden' class='discount' value='".$discount."' readonly>"; 

                    echo '<input type="hidden" id="sub_url" value="'.$sub_url.'">';

                     echo "<input type='hidden' name='product_ref_id' value='".$product_ref_id."' >";

                    

                    include(__DIR__."/includes/price.php");



                        if(!$in_stock){

                            echo "<div class='msg msg-alert mt-3 block'>".Sentence::translate("This product is sold-out")."</div>";

                        }

                        else
                    
                       
                        if($enable_shop){


                            echo "<div class='amount-wrapper'>";
                            

                                echo "<span class='glyphicon glyphicon-star' aria-hidden='true'></span>";
                            
                             

                                    echo "
                                    <input type='number' name='amount' value='1' 
                                    onchange='javascript:product_change_amount(this.value,0)'
                                    class='form-control 
                                    ";


                                    if($has_booking){

                                        echo " hidden ";

                                    }


                                    echo "'>";

                                
                                
                                    if($add_to_cart_button){

                                        
                                        echo "<button type='submit' class='add_to_cart add_to_cart_".$product_ref_id."'>";

                                            echo "
                                            <span class='loader'>
                                            <img src='/assets/images/interface/spinner.svg'>
                                            </span>
                                            ";


                                            // ADD TO CART

                                            

                                            echo "<span class='text'>";

                                                echo "<i class='fas fa-shopping-cart'></i>";
                                                

                                                if($has_booking){

                                                    $txt = "Book now";

                                                } else {

                                                    $txt = "Add to cart";

                                                }

                                                
                                                echo Sentence::translate($txt);

                                                                                        
                                            echo "</span>";


                                        echo "</button>";

                                    }


                            echo "</div>";


                            if($show_stock){


                                echo "<div class='stock'>";
                                    
                                    //if stock is below 10 and not unlimited
                                

                                    $sql = "select * from stock where product_ref_id = '".$product_ref_id."' and unlimited = 0 and amount <= 5";

                                    $almost_empty = DB::numrows($sql);

                                //($stock_amount < 10 and $stock_amount) or 

                                    if($almost_empty){

                                        echo "<i class='fas fa-circle yellow'></i> ".Sentence::translate("Limited stock");  

                                    } else {

                                        echo "<i class='fas fa-circle green'></i> ".Sentence::translate("In stock");  

                                    }

                                    
                                echo "</div>";


                            }


                        }

                        
                        
                       
                        


               
                    echo '<div class="share_wrapper">';

                        $url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

                        echo '<iframe src="https://www.facebook.com/plugins/share_button.php?href='.$url.'&layout=button&size=small&mobile_iframe=true&width=59&height=20&appId" width="59" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
                        
                    echo '</div>';
                

                echo "</div>";

                
                

            }
        
        ?>

        <?php

            include(__DIR__."/includes/tabs.php");
        
        ?>

    </div>

    
    <?php

        if($has_form){ echo "</form>"; }

    ?>



<?php

    }

?>
