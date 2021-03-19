<?php

    namespace App\Models\Formular\Formular;


    class Formular{
     
        
        public static $formular = array();

        public static $standard_fields = array();

        public static $object_name = array();

        public static $form_id_by_name = array();

        public static $form_name_by_id = array();
   

        public static function load(){



            if(!empty(self::$formular)){

                return self::$formular;

            }



            $form = array();

            $object_name = array();

            $standard_fields = array();

            $mysqli = DB::mysqli();



            $sql = "
            select *,
            frontend_formular.id as formular_id,
            frontend_formular_fields.id as field_id,
            frontend_formular.name as formular_name

            from frontend_formular 

            left join frontend_formular_fields on 
            frontend_formular.id = frontend_formular_fields.formular_ref_id

            where 

            frontend_formular.active = 1";




            $result = mysqli_query($mysqli,$sql);

            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){



                $formular_id = $row["formular_id"];
                $formular_name = $row["formular_name"];


                $form[$formular_id]["token"] = $row["token"];
                
                $form[$formular_id]["formular_id"] = $formular_id;   
                $form[$formular_id]["formular_name"] = $formular_name; 
                
                $form[$formular_id]["name"] = $row["name"];

                $form[$formular_id]["title"] = Sentence::get($row["title"]);
                $form[$formular_id]["description"] = Sentence::get($row["description"]);

           
                $form[$formular_id]["newsletter_email"] = $row["newsletter_email"];
                $form[$formular_id]["newsletter_sms"] = $row["newsletter_sms"];

                $form[$formular_id]["reciept_title"] = Sentence::get($row["reciept_title"]);
                $form[$formular_id]["reciept_message"] = Sentence::get($row["reciept_message"]);
                


                if(!empty($row["field_id"])){
                    

                    $form[$formular_id]["fields"][$row["field_id"]]["type"]  = $row["type"];
                    $form[$formular_id]["fields"][$row["field_id"]]["name"]  = $row["name"];
                    $form[$formular_id]["fields"][$row["field_id"]]["label"] = $row["label"];
                    $form[$formular_id]["fields"][$row["field_id"]]["value"] = $row["value"];
                
                    $form[$formular_id]["field_names"][$row["field_id"]] = $row["name"];


                    if($row["name"]){

                        $standard_fields[$row["name"]] = 1;

                    }
                    

                }
               

                
                self::$form_name_by_id[$formular_id] = $formular_name; 


                self::$form_id_by_name[$formular_name] = $formular_id; 

                

            }



            self::$standard_fields = $standard_fields;

            self::$formular = $form;

            self::$object_name = $object_name;


            return $form;


        }



        public static function get_formular_name_by_id($formular_id){


            self::load();
            

            if(isset(self::$form_name_by_id[$formular_id])){

                return self::$form_name_by_id[$formular_id];

            }

            
            return false;


        }



        public static function get_formular_id_by_name($formular_name){


            self::load();
           

            if(isset(self::$form_id_by_name[$formular_name])){

                return self::$form_id_by_name[$formular_name];

            }
            
            return false;

        }


        public static function get($arr = []){


            if(empty($arr)){ return false; }


            $object_ref_id = 0;
       
            extract($arr);


            if(!$object_ref_id){ return false; }


            $f = self::load();

         
            if(isset($f[$object_ref_id])){ return $f[$object_ref_id]; }
            
            return false;
            


        }


        public static function get_by_id($formular_ref_id){


            $sql = "
            select *,
            Components.name as object_name
            from 
            
            Components inner join frontend_formular on
            frontend_formular.object_ref_id = Components.id

            where 
            frontend_formular.object_ref_id = '".$formular_ref_id."'";

          

            $result = Format::current( DB::select($sql) );


            return self::get( $result["object_name"] );

        }



        public static function create($arr = []){


            
            $name = "";
            $token = "";
            $company = "";
            $cvr = "";
            $ean = "";
            $first_name = 1;
            $last_name = 1;
            $address = "";
            $city = "";
            $phone = 1;
            $email = 1;
            $newsletter_email = 0;
            $newsletter_sms = 0;
            $comment = 1;
            $active = 1;
            $name = "";
            $description = "";
            $reciept_title = "";
            $reciept_message = "";


            extract($arr);


  


            if(empty(self::$formular)){ self::load(); }
            
            $formular = self::$formular;


            // if formular exists

            
            if(!self::exists($name)){
                

    
                $arr = array(
                    "token"=>uniqid(),
                    "name"=>$name,
                    "active"=>$active,
                    "description"=>$description,
                    "reciept_title"=>$reciept_title,
                    "reciept_message"=>$reciept_message
                );
                    
                    
                $formular_ref_id = 
                DB::insert("frontend_formular",$arr);


                $arr["formular_id"] = $formular_ref_id;


                return $arr;



            } else {


                $f = Formular::get_formular_id_by_name($name);

                return $f;

            }



            return false;


        }


        public static function get_formular_by_token($token){


            if(empty($token)){ return false; }


            $result = self::load();


            if($result)
            foreach($result as $val){


                if($val["token"] == $token){

                   return $val; 

                }
                

            }

        }


        public static function exists($formular_name){


            $sql = "select id from frontend_formular where name = '".$formular_name."'";


            if(DB::numrows($sql)){

                return true;

            }

            return false;

        }


        public static function insert($arr){
            

            if(empty($arr)){ return false; }


            $ignore = false;

            $formular_ref_id = 0;

            $show_title = true;

            $show_wrapper = true;

            $has_form = true;

            $require_contact = false;
                  
            $create_if_not_exists = false;

            $button_text = 0;


            extract($arr);

          

            if(!$formular_ref_id){

               return false;

            }

      
            /*
            if($create_if_not_exists)
            if(!self::exists()){



            }
            */



            $formular = self::load();

            
            if(!$formular){ return false; }
           
            
            if(!isset($formular[$formular_ref_id])){
             
                return false;

            }


            $form = $formular[$formular_ref_id];
        
            if(empty($form["fields"])){ return false; }

            
            $fields = $form["fields"];
         


            ob_start();
            
            
            echo "<div class='form_wrapper'>"; 
            

                echo "<input type='hidden' name='formular_ref_id' value='".$form["formular_id"]."'>";
                echo "<input type='hidden' name='token' value='".$form["token"]."'>";


           

                    if($show_title)
                    if(!empty($form["title"]) or !empty($form["description"])){


                   

                        if($form["name"]){

                            echo "<h1 class='formular-title'>".$form["title"]."</h1>";

                        }

                                
                        if($form["description"]){

                            echo "<p class='formular-description'>".$form["description"]."</p>";

                        }
                        

                  

                    }
                        

        

                  //  include(__DIR__."/includes/fields.php"); 

                        
                    include(__DIR__."/includes/customer_info.php"); 
                
                        
                    include(__DIR__."/includes/submit.php"); 



                echo "</div>";

            
            
            $content =  ob_get_contents();

            ob_end_clean();


            return $content; 


        }



        public static function field($type,$name,$value,$label){

            
            if($type == "text"){ $type = "textfield"; }


            if($type == "checkbox"){    include(__DIR__."/includes/fields/checkbox.php"); }

            if($type == "number"){      include(__DIR__."/includes/fields/number.php"); }
    
            if($type == "textarea"){    include(__DIR__."/includes/fields/textarea.php"); }
    
            if($type == "textfield"){   include(__DIR__."/includes/fields/textfield.php"); }



            if(is_array($value)){

                if($type == "radio"){       include(__DIR__."/includes/fields/radio.php"); }

                if($type == "select"){      include(__DIR__."/includes/fields/select.php"); }

            }
            

        }

    }

?>