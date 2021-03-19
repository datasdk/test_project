<?php   

    namespace App\Models\Api\Api;

    class Price {

         
        public static function insert($price,$valuta_ref_id = 0){

            //$valuta = Valuta::get($valuta_ref_id)

            if(is_array($price))
            if(isset(current($price)["price"])){

                $valuta_ref_id = key($price);

                $price = current($price)["price"];

            }


            $valuta_code = Valuta::get_valuta_code($valuta_ref_id);
            
     

            if($valuta_code){

                $price = self::format($price,$valuta_ref_id);

                $price .= " ".$valuta_code;

                return $price;

            }

            return false;

        }


        public static function format($price,$valuta_ref_id = 0,$dblzero = true){


            if(!$valuta_ref_id){

                $valuta_ref_id = Valuta::get_default_valuta();

            }
         

            if($valuta_ref_id == 208){
                
                $price =  number_format( floatval($price) , 2, ',', '.');

            } else {
                
                $price =  number_format( floatval($price) , 0, ',', '.');

            }
            
                
            if(!$dblzero){

               $price = str_replace(",00",",-",$price);  

            }
            
            

            return $price;
    
        }


        public static function is_free($price){
            

            if($price <= 0){ return true; }

            if(floatval($price) <= 0){ return true; }

            return false;

        }


        public static function price_ext($price_ext = 0){


            
            if(!$price_ext){ return false; }


            $trans[1]  = Sentence::translate("per. day");
            $trans[2]  = Sentence::translate("per. week");
            $trans[3]  = Sentence::translate("per. month");
            $trans[4]  = Sentence::translate("per. year");

            $trans[5]  = Sentence::translate("per. minute");
            $trans[6]  = Sentence::translate("per. hour");
            $trans[7]  = Sentence::translate("per. quarter");

            $trans[8]  = Sentence::translate("per. PCS");
            $trans[9]  = Sentence::translate("per. kilo");
            $trans[10] = Sentence::translate("a meter");
            $trans[11] = Sentence::translate("a m2");

            $trans[12] = Sentence::translate("a liter");


            if($price_ext === "*"){

                return $trans;
                
            }



            $r = false;


            foreach($trans as $id => $val){

                $str = Sentence::translate($val);

                if($id == $price_ext){ $r = $str; }

            }

            
  
            return $r;

        }

    }