    

<?php
 
    echo "<label>".ucfirst($name)."</label>";
    

    echo "<select name='".$name."'>";


        foreach($value as $val => $label){
        
      
            echo "<option value='".$val."'>".$label."</option>";
            
            echo ucfirst($val);


        }

    echo "</select>";
        
?>

<