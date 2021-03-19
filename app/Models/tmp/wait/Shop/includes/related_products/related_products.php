<?php


    $products = array();

    $products_categories = Products::get_categories($product_ref_id);

    if(empty($products_categories)){ return false; }

    


    $categories = Categories::get(["categories" => $products_categories]);


    $all_products = current($categories)["products"];
                            
    shuffle($all_products);





    foreach($all_products as $arr){
                        
        // samme produkt må ikke vises på listen

        if($arr["id"] == $product_ref_id){

            continue;

        }


        $products[] = $arr["id"];

            
        if(count($products) >= $limit){

            break;

        }
   

    }


    $layout = layout::get("shoplist");
    
    $layout_shop_cart = 1;

    $enable_shop = Settings::get("enable_shop");


    $rules = false;


    if(!empty($products))
    $products = Products::get(["products" => $products]);

            


    if(!empty($products)){  



        echo '<div class="related_products_products">';


           // echo '<div class="showcase_wrapper">';

            
                    
                if($headline){

                    echo '<h1>'.Sentence::translate("Related products").'</h1>';

                }
                


                echo '<div class="related_products_content">';


                    echo '<div class="shop-list '.$layout.'">';


                        Shop::render_products(["products"=>$products]);


                    echo '</div>';


                echo '</div>';
            

          //  echo '</div>';


        echo '</div>';


    }

            
            

    ?>


