<?php


    echo "<div class='cart-overview-wrapper follow_scroll'>";


        include(__DIR__."/includes/header.php");
    
        include(__DIR__."/includes/message.php");

        
        
        if($has_products){


            echo "<div class='cart_overview_content'>";


                include(__DIR__."/includes/products.php");
                        
                    
            echo '</div>';


        }



        if($amount > 0){


            echo "<div class='cart_calculation'>";


                include(__DIR__."/includes/calculation.php");


            echo "</div>";



            if($button){


                $label = Sentence::translate("Checkout");

                $lang_url = Languages::lang_url();

                $url = $lang_url . "/checkout";

                
                echo "<a href='".$url."' class='cart-submit-button'>";
                
                echo ucfirst($label);
                
                echo "</a>";
                

            }

            

        }


    echo "</div>";

?>