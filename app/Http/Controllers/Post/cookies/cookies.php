<?php


    $expire = time()+(3600 * 24 * 7 * 2); // 2 weeks

    $cookie = setcookie("accept_cookies", 1 ,$expire,"/");
    

    echo json_encode(array("success" => $cookie));

?>