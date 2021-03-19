<?php

/*
    if(Editor::online()){
                            
        echo "
        <span onclick='javascript:admin_change_object(".$object_ref_id.",\"image\")' 
        class='far fa-images admin_edit'></span>";
            
    }
 */


  
    


    echo "
    <div id='obj-".$object_ref_id."' 
    class='frontend_image ".implode(" ",$class)."'
    >";



        echo "
        <img id='obj-".$object_ref_id."' src='".$src."' 
        class='frontend_image'
        alt='".$_SERVER["HTTP_HOST"]."' >";

 
        
        if($stamp){ 
            
           // echo $stamp_src; 
        
        }

            
    echo "</div>";

?>
