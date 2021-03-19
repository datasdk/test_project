<?php

    $newsletter = intval(isset($_POST["newsletter"]));

    Session::set("checkout_customer_accept_newsletter",$newsletter);

?>