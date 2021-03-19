<?php


    $title = Sentence::translate("Confirm the terms and conditions");
    $msg   = Sentence::translate("Read our terms and conditions and confirm by checking the box");



    $arr = ["title"=>$title,"msg"=>$msg];

    echo json_encode($arr);

?>