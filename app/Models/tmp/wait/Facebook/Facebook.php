<?php

  namespace App\Models\Api\Api;

    Class FB{

        public static $fb;

        public static function connect($app_id,$app_secret,$accessToken = ""){
            
            
          
            $fb = new \Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.10',
            'default_access_token' => $accessToken, // optional
            ]);
          
          
          
            try {
              // Get the \Facebook\GraphNodes\GraphUser object for the current user.
              // If you provided a 'default_access_token', the '{access-token}' is optional.
              $response = $fb->get('/me');

              self::$fb = $fb;

              return ["success"=>true,"response"=>$response];


            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
              // When Graph returns an error
              
              return ["success"=>false,"error"=>$e->getMessage()];
          
              exit;

            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
              // When validation fails or other local issues
          
              return ["success"=>false,"error"=>$e->getMessage()];
          
              exit;
            }
            

        }


        public static function login($callback){


          ob_start();


            if(!is_string($callback)){ return "Function must be a string"; }


            echo '<a href="javascript:facebook_login(\''.$callback.'\')" class="fb-button">';
            
              echo "<i class='fab fa-facebook-f'></i>";

              echo 'Login med Facebook';
            
            echo '</a>';

            
            $c = ob_get_contents();


          ob_end_clean();


          return $c;


        }

       

        public static function get_user_id(){

          return Variable::get("facebook_iser_id"); 

        }
        
        public static function set_user_id($iserID){

          return Variable::set("facebook_iser_id",$iserID); 

        }



        public static function get_access_token(){

          return Variable::get("facebook_access_token"); 

        }
        
        public static function set_access_token($iserID){

          return Variable::set("facebook_access_token",$iserID); 

        }

    }

?>