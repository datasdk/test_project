
<div id="customer_login_popup" class="customer_login">


    <?php


        $callback_url = "/";


        if(isset($_POST["currentLocation"])){

           $callback_url = $_POST["currentLocation"]; 

        }
        
/*
        
        if(!empty($_POST["callback_url"])){

            $callback_url = $_POST["callback_url"];

        }


        $sep = "?";

        if(Str::contains($callback_url,"?")){

            $sep = "&";

        }
 
        

        $callback_url .= $sep."status=login_succeed";
*/

        $lang = Language::get_code_by_language_id();

        echo Login::insert(["title"=>"Login","accept_url"=>$callback_url]);


    ?>


</div>