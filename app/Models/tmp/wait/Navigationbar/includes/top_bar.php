<?php


    $company = Company::get();


    if($company){

        
        $name = $company["company"];

        $logo = Logo::insert();

      
        echo "<div class='navigation_background'></div>";

        
        echo "<div class='navigationbar-top-bar shadow-sm'>";


            echo "<div class='logo_wrapper'>";
        

                if(!empty($logo)){ 
                            
                    echo "<a href='/'>".$logo."</a>";
                    
                }


            echo "</div>";



            $amount = Cart::amount();

            $enable_shop = Settings::get("enable_shop");     
            
            $shop_cart = Layout::get("shop_cart");



        
            if($shop_cart)
            if($enable_shop){

                echo "<a href='/cart' class='cart'><i class='fas fa-shopping-cart'></i>";

                    echo "<div class='amount Vcenter'>".$amount."</div>";
            
                echo "</a>";

            }
            

          


            if(!empty($navigationbar)){

                echo "<div class='menu'><i class='fas fa-bars'></i></div>";

            }
            

           
            /*

            denne funktion skal kunne slåes fra og til

            $url = "javascript:topmenu_customer_login()";
            

            if(Customer::is_logged_in()){

                $url = "/account";

            }



            echo "
            <div class='login-btn' data-url='".$url."'>
            <i class='fas fa-user'></i>
            </div>";

            */

            echo Language::insert(["float"=>"right"]);
            

        echo "</div>";

    }



?>