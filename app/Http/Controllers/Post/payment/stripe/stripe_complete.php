<?php


  $type = $_POST["type"];

  $return_url = $_POST["return_url"];

  $option = $_POST;

  $save_card = intval(isset($_POST["save_card"]));

  if(empty($return_url)){ $return_url = ""; }


  /*

  denne fjerner jeg fra smart i fart da den ikke har relevans..
  find en løsning der gør at når feltet ikke er der, så skal den ikke validere det.

  if($type == "payment" or $type == "redraw") 
  if(!isset($_POST["terms_of_trade"])){


    echo json_encode(["success"=>false,"terms_not_accepted"=>true]);

    die();

  }
*/



    

      $option["save_card"] = $save_card;    


      
      $res = Stripe::close($option);

     

      if(!empty($res["success"])){


        $o = Order::complete();


        if(empty($o["success"])){

          json_encode(["success"=>false,"msg"=>$o["msg"]]);

        }



      } else {


        json_encode(["success"=>false,"msg"=>$res["msg"]]);


      }




  echo json_encode(["success"=>true,"type"=>$type,"return_url"=>$return_url,"save_card"=>$save_card]);


?>

