<?php


    if($background_size == "cover"){  $class[]= "cover"; }

    if($background_size == "contain"){  
        
        $class[]= "contain"; 

        $class[]= "contain"; 
    
    }



    echo "
    <div id='obj-".$object_ref_id."' 
    class='frontend_image ".implode(" ",$class)."'
    style='background-image: url(".$src.")'
    >";

/*
        if(Editor::online()){
                            
            echo "
            <span onclick='javascript:admin_change_object(".$object_ref_id.",\"image\")' 
            class='far fa-images admin_edit'></span>";
            
        }
*/

        echo "
        <img id='obj-".$object_ref_id."' src='".$src."'
        class='frontend_image image-object-xs'
        alt='".$_SERVER["HTTP_HOST"]."' >";


    echo "</div>"; 

?>