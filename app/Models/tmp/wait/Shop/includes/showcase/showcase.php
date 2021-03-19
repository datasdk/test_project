<?php


    $mysqli = DB::mysqli();


    $products = array();

    $layout = Layout::get("shoplist");
    
    $layout_shop_cart = 1;
    
    $enable_shop = Settings::get("enable_shop");




    $rules = false;



    $products = Products::get(["rules" => ["showcase"]]);



    if(empty($products)){

        return false;

    }


    //uksort($products, function() { return rand() > rand(); });



    ob_start();


    $class = "";

    if($scroll_menu){

        $class = "scroll_menu";

    }


        echo "<div class='showcase_wrapper'>";

            
            echo "<div class='shop-list ".$layout." ".$class."'>";


                
                if($title or $description){


                    echo "<div class='header'>";

                        if($title){ echo "<h1>".$title."</h1>"; }
                        
                        if($description){ echo "<p>".$description."</p>"; }

                    echo "</div>";


                }


            
                Shop::render_products(["products"=>$products]);

            

            echo "</div>";


        echo "</div>";



    $content =  ob_get_contents();


    ob_end_clean();

        
    return $content;

?>