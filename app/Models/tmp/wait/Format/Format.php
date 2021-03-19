
<?php

    namespace App\Models\Api\Api;

    class Format{


        public static function current($val){

            if(is_array($val)){

                return current($val);

            }

            return $val;

        }

        public static function strtolower($val){

           return mb_strtolower($val, 'UTF-8');

        }


     

        public static function number($val,$dblzero = false,$decimals = true){


            if($decimals){

                $number =  number_format(floatval($val), 2, ',', '.');

            } else {
                
                $number =  number_format(floatval($val), 0, ',', '.');

            }
            
                
            if(!$dblzero)
            $number = str_replace(",00",",-",$number); 
            


            return $number;
    
        }

        public static function dot_seperator($val,$decimal_symbol = ","){


            if($decimal_symbol == ".")
            $val = number_format($val, 2, '.', '');


            if($decimal_symbol == ",")
            $val = number_format($val, 2, ',', '');
            

            $val = str_replace(array(".00",",00"),"",$val);

            return $val;
        
        }


        public static function encode_aeoeaa($str){

            $conver_chars = array("æ","Æ","ø","Ø","å","Å");

            foreach($conver_chars as $char){ 

                $str = trim(str_replace($char,urlencode($char),$str));
    
            }

            return $str;

        }

        public static function decode_aeoeaa($str){

            $conver_chars = array("æ","Æ","ø","Ø","å","Å");

            foreach($conver_chars as $char){ 

                $str = trim(str_replace(urlencode($char),$char,$str));
    
            }

            return $str;
            
        }


        public static function dblzero($val){

            if($val > 10){

                return $val."0";

            }

            return $val;

        }



        public static function date($date){

            return $date;

        }


        public static function url_encode($val){

            $val = trim($val);

            $val = Format::strtolower(trim($val));

            $val = str_replace(" ","-",$val);

            $val = preg_replace('/[^a-åA-Å0-9-_\.]/','', $val);

            return $val;
 
        }


        public static function sort($arr){

            uasort($arr, array("self",'compfunc'));

            return $arr;
        }


        public static function compfunc($a, $b){

            if(!isset($a['sorting']) OR !isset($b['sorting']) ){ return false; }

            return $a['sorting'] - $b['sorting'];
        
        }


        public static function dateDiffTs($start_ts = 0, $end_ts = 0) {

            


            $diff = $end_ts - $start_ts;
            return round($diff / 86400);
        }


        public static function convert_content($content){

            $replace = [
            "<table"=>"<div class='cl-container important'",
            "<tbody>"=>"",
            "</tbody>"=>"",
            "<tr"=>"<div class='cl-row margin'",
            "</tr>"=>"</div>",
            "<td"=>"<div class='cl-col'",
            "</td>"=>"</div>",
            "</table>"=>"</div>",
            "´"=>""
            ];

            
            foreach($replace as $form => $to){

                $content = str_replace($form,$to,$content);

            }
            

            return $content;
    
        }


        public static function merge_array($arr1,$arr2){

            return array_merge($arr1, $arr2);

        }


        public static function  crypt($string){
            return urlencode(htmlspecialchars_decode($string, ENT_QUOTES));
        }

        
        public static function  uncrypt($string){
            return urldecode($string);
        }


        function stringInsert($str,$insertstr,$pos){
            
            $str = substr($str, 0, $pos) . $insertstr . substr($str, $pos);
            return $str;
        }  
        


        
		public static function parse($input){

            ob_start();


            return ob_get_contents();

            ob_end_clean();


        }
        

    }

?>