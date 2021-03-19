<center>
<h1>Payment failed</h1>

<?php



    if(isset($_GET["declineurl"])){


        $declineurl = $_GET["declineurl"];


        echo '<p><a href="'.$declineurl.'">Click here</a> to return to the website</a>';


    }

    
?>

</center>