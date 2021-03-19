<?php

    class Terms{


        public static function get($type=false,$invisible=false){


            $sql = "select * from settings_terms ";
            

            if($type){
                
                $sql.= "where type = '".$type."'";

            }
            
            
            $result = DB::select($sql);

            if(empty($result)){ 
                
                return false; 
            
            }

            
            $class = "";

            if($invisible){

                $class = "is_invisible";

            }


            $content = "<div class='terms_privatepolicy ".$class."'>";

            $no_terms = true;


                foreach($result as $arr){


                    $content .= "<div class='terms-contents'>"; 
                    
                    $c = Sentence::get($arr["content"]);


                    if(!empty($c)){
           
                        $no_terms = false;

                        $content .= $c;

                    } 

                    $content .= "</div>";


                }

                
                if($no_terms){

                    $content .= "<div class='p-4'><strong>".Sentence::translate("No terms found")."</strong></div>";

                }
                


            $content .= "</div>";
            
            return $content;

            
        }


        public static function standard(){


            include(__DIR__."/includes/terms_of_trade.php");

            return nl2br($terms);


        }


        public static function accept(){


            ob_start();


                echo '<div class="terms_of_trade_wrapper">';

                    echo '
                    <label>

                        <input type="checkbox" name="terms_of_trade" value="1">
                        '.Sentence::translate("I have read and accept").'
                        
                        <a href="/terms" target="blank">
                        '.Sentence::translate("Terms and private policy").'
                        </a>

                    </label>
                    ';

                echo '</div>';


                $c = ob_get_contents();


            ob_end_clean();


            return $c;


        }

    }

?>