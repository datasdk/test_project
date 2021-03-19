<?php
  
    if(isset($_POST["iserID"]))
    if(isset($_POST["accessToken"])){


        $iserID = $_POST["iserID"];
        $accessToken = $_POST["accessToken"];
        

        FB::set_user_id($iserID);
        FB::set_access_token($accessToken);

        
        echo json_encode(["iserID" => $iserID, "accessToken" => $accessToken]);

    }
    

?>