
<?php

    namespace App\Models\Api\Api;

    Class Google_analytics{



        public static function get(){


            $sql = "select * from google_analytics 
            where active = 1 limit 1";

            $result = DB::select($sql);
            
            
            foreach($result as $val){
                

                $analytic_id = strip_tags($val["analytic_id"]);

                
               
                if(!empty($analytic_id)){
                

                    echo "
                    <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                    
                    ga('create', '".$analytic_id."', 'auto');
                    ga('send', 'pageview');
                    
                    </script>
                    ";	
                    
                    
                    if(empty($_COOKIE["accept_cookies"])){

                        self::cookie_message();

                    }
                    

                }

                
            }	
            


            return false;


        }



        public static function cookie_message(){


            include(__DIR__."/includes/cookies.php");


        }


    }

?>