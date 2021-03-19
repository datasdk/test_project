<?php
    

    $block = 0;
    
    $groupes = array();

    $submenu_links = array();

  
   
    // LINKS

    if(!empty($submenu)){

     
      
        if(!empty($submenu)){


            echo "<div class='groupe'>";

               // echo "<div class='navigation-headline'>Kategorier</div>";

                foreach($submenu as $val){

                    $class = "";

                    echo "<li><a href='".$val["link"]."' ".$class.">".ucfirst($val["name"])."</a></li>"; 
                        
                }

            echo "</div>";


        }

        
    }



?>