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
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */
        img {
          border: none;
          -ms-interpolation-mode: bicubic;
          max-width: 100%; }
        body {
          background-color: #f6f6f6;
          font-family: sans-serif;
          -webkit-font-smoothing: antialiased;
          font-size: 14px;
          font-weight:normal !important;
          line-height: 1.4;
          margin: 0;
          padding: 0;
          -ms-text-size-adjust: 100%;
          -webkit-text-size-adjust: 100%; }
        table {
          border-collapse: separate;
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          width: 100%; }
          table td {
            font-family: sans-serif;
            font-size: 13px;
            vertical-align: top; }
        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        .body {
          background-color: #f6f6f6;
          width: 100%; }
        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
          display: block;
          Margin: 0 auto !important;
          /* makes it centered */
          max-width: 580px;
          padding: 10px;
          width: 580px; }
        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
          box-sizing: border-box;
          display: block;
          Margin: 0 auto;
          max-width: 580px;
          padding: 10px; }
        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
          background: #ffffff;
          border-radius: 3px;
          width: 100%;
          padding:20px; 
        }
        .wrapper {
          box-sizing: border-box;

        }
        .content-block {
          padding-bottom: 10px;
          padding-top: 10px;
        }
        .footer {
          clear: both;
          Margin-top: 10px;
          text-align: center;
          width: 100%; }
          .footer td,
          .footer p,
          .footer span,
          .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center; }
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
          color: #000000;
          font-family: sans-serif;
          font-weight: 400;
          line-height: 1.4;
          margin: 0;
          Margin-bottom: 30px; 
        }
        h1 {
          font-size: 35px;
          font-weight: 300;
          text-align: center;
           }
        p,
        ul,
        ol {
          text-align: center;
          font-size: 16px;
          font-weight: normal;
          margin: 0;
          Margin-bottom: 25px; 
        }
          p li,
          ul li,
          ol li {
            list-style-position: inside;
            margin-left: 5px; }
        a {
          color: #3498db;
          text-decoration: underline; }
     
      </style>
    </head>


    <body class="">
      <table border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
          <td>&nbsp;</td>
          <td class="container">
            <div class="content">

              <!-- START CENTERED WHITE CONTAINER -->
              <table class="main">

                <!-- START MAIN CONTENT AREA -->
                <tr>
                  <td style="line-height:170%">
                  
                    <div class="wrapper"> 

                      <?php

                        echo  $message;
                      
                      ?>
                    
                    </div>

                  </td>
                </tr>

              <!-- END MAIN CONTENT AREA -->
              </table>

              <!-- START FOOTER -->
              <div class="footer">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="content-block">
                      <span class="apple-link">

                      <?php

                        echo $companyname." / Cvr: ".$cvr." / ".$address." / ".$zipcode." ".$city."<br><br>";
                        echo $phone."<br>";
                        echo $email."<br>";
                      
                      ?>
                      
                      
                      
                      </span>
                    
                    </td>
                  </tr>
                  <tr>
                    <td class="content-block powered-by">
                      <!--Email afsendt igennem DATAS-->
                    </td>
                  </tr>
                </table>
              </div>
              <!-- END FOOTER -->

            <!-- END CENTERED WHITE CONTAINER -->
            </div>
          </td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </body>
  </html>

<?php

  $message = ob_get_contents();

  ob_end_clean();

?>

