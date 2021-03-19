<?php

    
    namespace App\Models\Opening_hours;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use DB;
    use Settings;


    class Opening_hours extends Model{


        use HasFactory;

        public static $openingHours;

        public static $closing_days;


        public static function loads(){


            $openingHours = array();

        
            $closing_days = array();


            // CLOSING DAYS

            
            
            $sql = "select * from settings_special_closingdays";
            
            $result = DB::select($sql);

            foreach($result as $val){

                $timestamp = strtotime("midnight",$val["timestamp"]);

                $closing_days[$val["company_ref_id"]][$timestamp] = $timestamp;

            }


            // OPENING HOURS
            
            $sql = "select * from opening_hours";

            $result = DB::select($sql);


            foreach($result as $row){
                

                $openingHours[$row->company_ref_id][$row->day]["from"]  = $row->from_hours.":".str_pad($row->from_minutes, 2, '0', STR_PAD_LEFT);
                $openingHours[$row->company_ref_id][$row->day]["to"]    = $row->to_hours.":".str_pad($row->to_minutes, 2, '0', STR_PAD_LEFT);

                $openingHours[$row->company_ref_id][$row->day]["closed"] = $row->closed;


            }


            self::$closing_days = $closing_days;

            self::$openingHours = $openingHours;
            
            return $openingHours;

        }


        public static function get($company_ref_id = 0){


            if(empty(self::$openingHours)){ self::loads(); }

                
            $openingHours = self::$openingHours;


            if(empty($company_ref_id)){ 
                    
                $openingHours = current($openingHours); 
                
            }
            else if(isset($openingHours[$company_ref_id])){ 
                    
                $openingHours = $openingHours[$company_ref_id]; 
                
            }
            else { 
                    
                return false; 
                
            }


            return $openingHours;

        }



        public static function insert($arr = []){
            

            $company_ref_id = 0;

            extract($arr);

            $openingHours = self::get($company_ref_id );

            if(!$openingHours){ return false; }

            
            include(__DIR__."/includes/standard.php");

     
        }



        


        public static function is_open($company_ref_id = 0,$timestamp = false,$update_outdated = true){

            
            if(!$timestamp){ $timestamp = time(); }

            if($update_outdated)
            if($timestamp < time()){ $timestamp = time(); }


            $openingHours = self::get($company_ref_id );

            $today = $openingHours[date("N",$timestamp)];

            $closed = false;    



            $close_after_closing_time = Settings::get("close_webshop_after_closing_time");

            $min_before_close = Settings::get("close_shop_x_min_before_closingtime");



            $now = $timestamp;

            
            $from = strtotime($today["from"],$timestamp);

            $to   = strtotime($today["to"],$timestamp);



            $close_before = 0;

            if($close_after_closing_time){

                $close_before = ($min_before_close * 60);

            }
            

            
            $special_closing_day = self::is_special_closing_day($timestamp);

            if($special_closing_day){

                return false;

            }


            if($today["closed"] or ($now < $from  || $now >= $to - $close_before)){

                return false;

            } else {

                return true;

            }

        }



        public static function is_special_closing_day($timestamp = false,$company_ref_id = 1){

            $closing_days = self::$closing_days;

            if(!$timestamp){ $timestamp = time(); }

            $timestamp = strtotime("midnight",$timestamp);

            
            if(isset($closing_days[$company_ref_id][$timestamp])){

                return true;

            }

            return false;

        }


        public static function merge_time($arr){

            

        }

    }

?>