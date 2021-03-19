
<?php

    namespace App\Models\Api\Api;


    class Input{
     

        public static function radio($name,$name_values,$options = []){

            return self::insert("radio",$name,$name_values,$options);

        }


        public static function checkbox($name,$label,$values,$options = []){

            $name_values = [$label => $values];

            return self::insert("checkbox",$name,$name_values,$options);

        }


      


   
        public static function insert($type,$name,$name_values,$options = []){


            $f = $name_values;

            $callback = "";
            $checked = false;
            $hide = false;

            extract($options);



            echo "<div class='input-wrapper";
            
            if($hide){ echo " hide "; }
            
            echo "'>";


                foreach($name_values as $label => $val){


                    $c = "";

                    if($checked){


                        if($type == "radio")
                        if($checked == $val){

                            $c = 'checked';

                        }


                        if($type == "checkbox"){ 
                            
                            $c = 'checked'; 
                        
                        }


                    }
                    


                    echo "<div class='input radio' data-callback='".$callback."'>";


                        if($type == "radio"){

                            echo "
                            <input id='".$name."' type='radio' name='".$name."' 
                            value='".$val."' placeholder = '".$label."' ".$c.">";

                        }


                        if($type == "checkbox"){

                            echo "
                            <input id='".$name."' type='checkbox' name='".$name."' 
                            value='".$val."' placeholder = '".$label."' ".$c.">";

                        
                        }
                        

                        echo "<label for='".$name."'>".$label."</label>";
                        
                    echo "</div>";



                }

                
            echo "</div>";

            
        }

    }

?>