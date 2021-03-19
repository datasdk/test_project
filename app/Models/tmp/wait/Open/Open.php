
<?php

    namespace App\Models\Api\Api;


    Class Open{


        public static function get($company_ref_id = 0,$show_open=true,$show_close=true){

            $title = false;

            $msg = false;

            $is_open = OpeningHours::is_open($company_ref_id);

            $enable_shop = Settings::get("enable_shop");

            $close_after_closing_time = Settings::get("close_webshop_after_closing_time");

            

            if($is_open){


                $title = Sentence::translate("We are open now");

                $close = false;

            } else {


                $title = Sentence::translate("We've closed now");
                

                if($enable_shop)
                if($close_after_closing_time){
    

                    $msg = Sentence::translate("You can't make an order now when we have closed");


                } else {
    

                    $msg = Sentence::translate("However, you can still place orders on the page.");


                }

                $close = true;
            
            }
    
                

                if($is_open){

                    if($close_after_closing_time){
    
                        $msg .= Sentence::translate("Its not possible to order after")." ".date("d/m Y H:i",$to - $close_before);
        
                    }
    
                }
                


                if(!$show_open and !$close){ return false; }

                if(!$show_close and $close){ return false; }
                

    
                if($is_open){
                
                    echo "<div class='openingHours_banner openingHours_open'>";
                
    
                } else {
    
                    echo "<div class='openingHours_banner openingHours_closed'>";
    
                }
                

                if($title){

                    echo "<p>".$title."</p>";

                }
                
                
                if(isset($msg)){

                    echo "<p>".$msg."</p>";

                }
                
                
                echo "</div>";
            

        }

    }

?>