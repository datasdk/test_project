<?php
    


    if(isset($form["fields"]))
    if(is_array($form["fields"]))
    foreach($form["fields"] as $field_id => $val){


        $type   = $val["type"];
        $name   = $val["name"];
        $label  = $val["label"];
        $value  = $val["value"];


        
        if($type == "checkbox"){    include(__DIR__."/fields/checkbox.php"); }

        if($type == "number"){      include(__DIR__."/fields/number.php"); }

        if($type == "radio"){       include(__DIR__."/fields/radio.php"); }

        if($type == "textarea"){    include(__DIR__."/fields/textarea.php"); }

        if($type == "textfield"){   include(__DIR__."/fields/textfield.php"); }

        

    }


?>