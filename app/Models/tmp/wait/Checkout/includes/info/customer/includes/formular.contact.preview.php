<?php


    $firstname      = $c["firstname"];
    $lastname       = $c["lastname"];
    $address        = $c["address"];
    $housenumber    = $c["housenumber"];
    $floor          = $c["floor"];
    $zipcode        = $c["zipcode"];
    $city           = $c["city"];
    $email          = $c["email"];
    $phone          = $c["phone"];

?>


<div class="address_overview">

    <div class='contact_information'>
        <div><?php echo $firstname ." ".$lastname; ?></div>
        <div><?php echo $address ." ".$housenumber." ".$floor; ?></div>
        <div><?php echo $zipcode ." ".$city; ?></div>
        <div><?php echo "E-mail: ".$email; ?></div>
        <div><?php echo "Tlf. ".$phone; ?></div>
    </div>
    


    <i class="change_address fas fa-pencil-alt"></i>


</div>