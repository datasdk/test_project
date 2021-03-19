<?php


    echo "<div class='cart_overview'>";


        include(__DIR__."/includes/header.php");
    
        include(__DIR__."/includes/message.php");

        

        echo "<div class='cart_overview_content'>";

            
            include(__DIR__."/includes/products.php");
                
            if($amount > 0){

                include(__DIR__."/includes/calculation.php");

            }
            

            
        echo '</div>';



        if($promotioncode){

            include(__DIR__."/includes/promotioncode.php");

        }

        


        if($showcase){


            $rec = Shop::showcase();


            if($rec){


                echo "<div class='recommended_products'>";

                    echo "<h4>".Sentence::translate("Recommended products")."</h4>";

                    echo $rec;

                echo "</div>";


            }


        }

        

    

    echo "</div>";

?>