<?php


    $form = Formular::create(["name"=>$formular_name]);

    


    echo "<div class='booking-wrapper'>";

                
        echo "<form method='post' onsubmit='return booking_submit(this)'>";


            echo "<input type='hidden' name='product_ref_id' value='".$product_ref_id."'>";

            $hide_products = false;


            if($p)
            if(count($p) == 1){

                $title = Format::current($p);

                $hide_products = true;

            } 


            

            if(!empty($form["name"]) or !empty($form["description"])){
        

                echo "<div class='booking-title'>";

                
                    if(!empty($form["name"])){

                        echo "<h2>".$form["name"]."</h2>";

                    }
                    
                    if(!empty($form["description"])){

                        echo "<p>".$form["description"]."</p>";

                    }
                    

                echo "</div>";

            }
            


            if(isset($title)){
        

                echo "<div class='booking-title'>";

                    echo "<h2>".$title["name"]."</h2>";

                    echo "<p>".Text::limited($title["description"],150)."</p>";

                echo "</div>";

            }
            

            $class = "";

            if($hide_products){ $class = "hide"; }
    
    
            $o = self::insert_product_option($product_ref_id,$variants);


            if($o){

                echo "<div class='booking-product-options ".$class."'>";

                    echo "<span class='booking-label'>".Sentence::translate("Select event")."</span>"; 

                    echo $o; 

                echo "</div>";

                // variants
            
                echo "<div class='booking-variant-wrapper'></div>";
            
            }



            $o = self::insert_person_option($product_ref_id);

            
            if($o){

                echo "<div class='booking-person-options'>";

                    echo "<span class='booking-label'>".Sentence::translate("Select the number of persons")."</span>";

                    echo $o;

                echo "</div>";

            }


            
            echo "<div class='booking-date-options'>";

                echo "<span class='booking-label'>".Sentence::translate("Select date and time")."</span>";

                echo "<div class='booking-date-options-content'></div>";

            echo "</div>";




            if(isset($form["object_ref_id"])){


                $object_ref_id = $form["object_ref_id"];


                echo "<div class='booking-contact-formular'>";


                    Frontend::insert($object_ref_id,["has_form"=>0]);


                echo "</div>";


            }
            
            
            echo reCaptsha::insert();



            echo "
            <div class='booking-footer'>

                <button class='btn booking-submit'>BOOK NU</button>

            </div>";


            
        echo "</form>";


    echo "</div>";
