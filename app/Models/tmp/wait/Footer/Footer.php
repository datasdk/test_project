<?php

    namespace App\Models\Footer;

    Class Footer{


        public static function insert($col1=false, $col2=false, $col3=false, $col4=false){



            if(!$col1){ $col1 = self::address(); }
            if(!$col2){ $col2 = self::contact(); }
            if(!$col3){ $col3 = self::openinghours(); }
            if(!$col4){ $col4 = self::follow_us(); }


            $opt = [
                "xl"=>4,
                "lg"=>4,
                "md"=>4,
                "sm"=>2,
                "xs"=>1
            ];

            echo Col::insert("footer", [ $col1, $col2, $col3, $col4 ] ,$opt);

    
        }

        
        

        public static function address(){


            ob_start();


                echo "
                <h4 class='footer-title'>
                ".Sentence::translate("Our address")."
                </h4>";

                echo Company::info();

                echo Text::get("footer-address-text");

                $content =  ob_get_contents();


            ob_end_clean();


            return $content;
                
        }



        public static function contact(){


            $lang_url = Languages::lang_url();


            ob_start();

                echo "
                <h4 class='footer-title'>
                ".Sentence::translate("Contact")."
                </h4>
                ";


                echo Company::phone();

                echo Text::get("footer-contact-us-email");

                
                $content =  ob_get_contents();


            ob_end_clean();


            return $content;


        }



        public static function openinghours(){

            ob_start();

                echo "
                <h4 class='footer-title'>
                ".Sentence::translate("Openinghours")."
                </h4>";
            
                OpeningHours::insert();

                echo Text::get("footer-openinghours-text");

                $content =  ob_get_contents();

            ob_end_clean();

            return $content;

        
        }



        public static function follow_us(){


            ob_start();

                echo "
                <h4 class='footer-title'>
                ".Sentence::translate("Follow us")."
                </h4>";

                echo Socialmedia::insert();

                $content =  ob_get_contents();

            ob_end_clean();

            return $content;

  
        
        }



    }

?>