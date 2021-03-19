<?php

    
function create_email_content($title,$description,$form,$specifications,$info){

    
      
    $message = '
    <h1>'.$title.'</h1>
    <p>'.$description.'</p>
    <hr>
    ';


    $message .= '
    <table width="100% border="0" cellspacing="0" cellpadding="0" style="line-height:170%">
    ';


    if(!empty($specifications))
    foreach($specifications as $field_id => $val){


        $message .= '
        <tr >
        <td>
        '.ucfirst($val["name"]).'
        </td>
        </tr>

        <tr>
        <td >';
        

        if(is_array($val["value"])){ 
            
            $message .= "<li>".implode("</li><li>",$val["value"])."</li>"; 
        
        }
        else { 
            
            $message .= $val["value"]; 
        
        }
        
        
        $message .= '
        
        </td>
        </tr>
        ';

    }

    /**
     * AFSENDER
     */


    $message .= '
    <tr>
    <td >
    ';


    if(!empty($info["comment"])){

        $message .= '
        <div><strong>Besked:</strong></div>
        <div>'.$info["comment"].'</div>
        ';

    }
  
    
    $message .= '<hr>';


    if(!empty($info["company"]) or !empty($info["cvr"])){

        
        $message .= '<div>';
        
        $message .= $info["company"];
        

        if(!empty($info["cvr"])){

            $message .= ' / cvr. '.$info["cvr"];

        }
    

        $message .= '</div>';

    }


    if(!empty($info["firstname"])){


        $message .= '<div>';

        if(isset($info["firstname"])){ $message .= ' '.$info["firstname"]; }

        if(isset($info["lastname"])){ $message .= ' '.$info["lastname"];; }
        

        $message .= '</div>';


    }
    
    
    if(!empty($info["address"])){

        $message .= '<div>'.$info["address"].'</div>';

    }
    


    if(!empty($info["city"])){

        $message .= '<div>'.$info["zipcode"].' '.$info["city"].'</div>';

    }
    


    $message .= '<br>';


    if(!empty($info["email"])){

        $message .= '<div><strong>E-mail:</strong> '.$info["email"].'</div>';

    }
    

    $message .= '<br>';

    if(!empty($info["phone"])){

        $message .= '<div><strong>Telefon:</strong> '.$info["phone"].'</div>';

    }
    


    $message .= '
    <td>
    </tr>
    ';

    
    $message .= '

    </table>
    ';

    return $message;

}



?>