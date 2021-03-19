<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    class Email{


        public static $host;
        public static $username;
        public static $password;
        public static $port;

        public static $mailserver_info;


        public static function load(){
           
            $r = Format::current( DB::select("select * from mailserver") );

            if(!$r){ return false; }


            self::$host = $r["host"];
            self::$username = $r["username"];
            self::$password = $r["password"];
            self::$port = $r["port"];

        }


        public static function send($to, $subject, $message, $attachments = false,$layout=false,$reply_to=false,$debug=false){


           if(empty(self::$host)){ self::load(); }


            if(
                empty(self::$host) or
                empty(self::$username) or
                empty(self::$port)            
            ){ 

                return false;

            }

            

            if(DB::is_localhost()){

                $to = "datas.webbureau@gmail.com";

            }
           
           

            $company = Company::get();

            $companyname = $company["company"];
            $address = $company["address"];
            $zipcode = $company["zipcode"];
            $city = $company["city"];
            $cvr = $company["cvr"];


            $host = self::$host;
            $username = self::$username;
            $password = self::$password;
            $port = self::$port;


            if(!$reply_to){

                $reply_to = Format::current(Company::get_all_emails("reply",true));

            } 
            
            
            $phone = Company::phone();
            $email = Company::email("support");

           
            
                    
            if($layout){

                include(__DIR__."/includes/layout/standard.php"); 
        
            }
            
        



            $mail = new PHPMailer(true);
        
            try {
                //Server settings
                
                if($debug){ $mail->SMTPDebug = SMTP::DEBUG_SERVER; }
                else { $mail->SMTPDebug = false; }

                
                //SMTP::DEBUG_CONNECTION;
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $host;                    // Set the SMTP server to send through
                $mail->Username   = $username;                     // SMTP username
                $mail->Password   = $password;                               // SMTP password
                $mail->Port       = $port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->CharSet    = 'UTF-8';

                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                
          /*
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
             */


                //Recipients
                $mail->setFrom($username, $companyname); 
                $mail->addReplyTo($reply_to, $companyname);
                
        
                // to
        
                if(!is_array($to)){ $to = array($to); }
        
                foreach($to as $to_email){
                            
                    if(self::validate($to_email)){
                                                
                        $mail->addAddress($to_email);
            
                    }
                            
                }
        
                    // Add a recipient
        
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');
        
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = ucfirst($subject);
                $mail->Body    = $message;
                $mail->AltBody = $message;
            
            
                //attachments
        
                if($attachments){
                    
                    foreach($attachments as $file_name => $pdf_path){
            
                        if(empty($file_name) or empty($pdf_path)){ continue; }
                
                        $mail->addAttachment($pdf_path, $file_name.".pdf");    // Optional name
        
                    }
            
                }
            
        
                $mail->send();
        

                return ["success"=>true];

        
        
            } catch (Exception $e) {    
        
                $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        
                Write::log($msg);
        
                return ["success"=>false,"msg"=>$msg];
        
            }
   
  
         
        
        }
        


        public static function validate($email){
        
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) or empty($email)) {
                
                return false;
            
            }
            else {
                
                return true;
            
            }
        
                
        }

    
      

    }

?>