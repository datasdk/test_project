<?php
    
 
    echo "<label>".ucfirst($label)."</label>";


    foreach($value as $val => $label){
        
        echo "<label>";
                
        echo "<input type='radio' name='".$name."' value='".$val."'> ".ucfirst($label);

        echo "</label>";
            

    }
            
?>

