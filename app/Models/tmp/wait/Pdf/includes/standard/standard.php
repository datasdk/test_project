<?php

  ob_start();

?>



  <!doctype html>
  <html>
    <head>
      <meta name="viewport" content="width=device-width" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Email</title>
      <style>
        
        body{
            font-family: arial, sans-serif; 
            font-size:14px;
            color:#333;
            line-height:170%;     

        }


        body *{
            
            padding:0px !important;
            margin:0px !important;
        }

            
                
      </style>
    </head>
    <body >
    

        <?php

            echo  $html;
                      
        ?>
                    
       
    </body>
  </html>

<?php

  $html = ob_get_contents();

  ob_end_clean();

?>




