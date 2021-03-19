<?php


    $object_ref_id = $_GET["object_ref_id"];

    $type = $_GET["type"];


    $arr = array("object_ref_id"=>$object_ref_id,
                 "type"=>$type,
                );

    Json::set("admin_edit_website",$arr,"http://local.admin.datas.dk");


?>