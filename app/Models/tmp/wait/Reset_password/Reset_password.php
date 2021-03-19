<?php
    
    namespace App\Models\Api\Api;

    class Reset_password{
 

        public static function insert(){

           
  
            echo '<div id="change_password">';
            
              

                if(isset($_GET))
                if(isset($_GET["key"])){
                        
                        
                    $key = $_GET["key"];
                        
                        
                    $sql = "select * from website_forgot_password 
                    where key_ref = '".$key."' and  date > ".strtotime("-15 minutes")." ";
            
                    $result = DB::select($sql);
                        
                        
                    if(count($result) > 0){
                        
                        
                        include(__DIR__."/includes/change_password.php");
                            
                        
                    } else {
                        
            
            
                        include(__DIR__."/includes/error.php");	
                            
                        
                    }
                        
                    
                }
            

            echo "</div>";    


        }

    }

?>
            