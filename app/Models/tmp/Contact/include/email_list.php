<?php


    $suppor_emails = Company::get_all_emails("support");



    if(count($suppor_emails) > 1){


        echo "<div class='contact_receive_wrapper'>";    


            echo "<label>";

                echo "Vælg modtager";


                echo "<select name='receive'>";

                    foreach($suppor_emails as $id => $arr){

                        echo "<option value='".$id."'>".$arr["name"]."</option>";

                    }

                echo "</select>";


            echo "</label>";


        echo "</div>";


    }

   

?>