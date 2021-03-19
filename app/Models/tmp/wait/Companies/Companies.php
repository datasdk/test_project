<?php

    namespace App\Models\Companies;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use DB;
    use Sentence;


    class Companies extends Model{


        public static function get(){

        
            return Companies::all();


            $company = array();
            
            $sql = "
            select *,
            company.id as company_id,
            company.name as company_name,
            company_email.id as email_id,
            company_email.name as email_name,
            company_phone.id as phone_id,
            company_phone.name as phone_name
            from company
            
            left join company_email on
            company.id = company_email.company_ref_id

            left join company_phone on
            company.id = company_phone.company_ref_id

            order by 
            company_phone.sorting,
            company_phone.id,
            company_email.sorting,
            company_email.id
            ";
            

            $result = DB::select( DB::raw($sql) );

            dd($result->owner);

            foreach ($result as $row){


                $company["id"] = $row["company_id"];
                $company["owner"] = $row["owner"];
                $company["company"] = $row["company_name"];
                $company["cvr"] = $row["cvr"];
                $company["address"] = $row["address"];
                $company["housenumber"] = $row["housenumber"];
                $company["floor"] = $row["floor"];
                $company["zipcode"] = $row["zipcode"];
                $company["city"] = $row["city"];
                $company["vat_registered"] = $row["vat_registered"];
                $company["active"] = $row["active"];

                
                $company["bank"]["iso"] = $row["iso"];
                $company["bank"]["control_key"] = $row["control_key"];
                $company["bank"]["registration"] = $row["registration"];
                $company["bank"]["account_no"] = $row["account_no"];


                $company["logo_image_ref_id"] = $row["logo_image_ref_id"];
                $company["og_image_ref_id"]     = $row["og_image_ref_id"];
                $company["favicon"]     = $row["favicon"];
                

                $company["lat"] = $row["lat"];
                $company["lng"] = $row["lng"];
               

                

                if($row["phone_id"]){
                    
                    $company["phone"][$row["phone_id"]]["name"] = Sentence::get($row["phone_name"]);
                    $company["phone"][$row["phone_id"]]["number"] = $row["number"];
                    $company["phone"][$row["phone_id"]]["main_number"] = $row["main_number"];

                }
                


                if($row["email_id"]){

                    
                    $company["email"][$row["email_id"]]["name"] = Sentence::get($row["email_name"]);
                    $company["email"][$row["email_id"]]["email"] = $row["email"];
                    $company["email"][$row["email_id"]]["support"] = $row["support"];
                    $company["email"][$row["email_id"]]["transactions"] = $row["transactions"];
                    $company["email"][$row["email_id"]]["jobmail"] = $row["jobmail"];
                    $company["email"][$row["email_id"]]["reply"] = $row["reply"];
                  
                }
            


            }
            

            if(empty($company)){

                return false;

            }


            return $company;

        }


        
        public static function info(){


            $company = Companies::get();


            $return  = "";


            if(!empty($company["company"])){

                $return .= "<div>".$company["company"]."</div>";

            }
            

            if(!empty($company["address"])){

                $return .= "<div>".$company["address"]."</div>";

            }
            

            if(!empty($company["zipcode"])){

                $return .= "<div>".$company["zipcode"]." ".$company["city"]."</div>";

            }
            

            if(!empty($company["cvr"])){

                $return .= "<div>CVR: ".$company["cvr"]."</div>";

            }
            

            
            return $return;

        }




        public static function phone(){

            
            $company = Companies::get();


            $html = "";

            $html .= "<div class='company_phone_wrapper'>";



            if(isset($company["phone"]))
            foreach($company["phone"] as $val){

                
                if(empty($val["number"])){ continue; }
                
                
                $html .=  "<div class='phone_name'>".ucfirst($val["name"])."</div>";

                $html .=  "<div class='phone_number'>";
                
                $html .=  "Tlf: <a href='tel:".$val["number"]."'>".self::formatPhoneNumber($val["number"])."</a>";
                
                $html .=  "</div>";

            }



            $html .=  "</div>";


            return $html;

        }



        
        public static function email($type="support",$hide_email_url = false){



            $company = Companies::get();



            $html = "";

            $html .= "<div class='company_email_wrapper'>";

            
            $emails = self::get_all_emails($type);


    
            foreach($emails as $val){
                

                $html .=  "<div class='email_name'>".ucfirst($val["name"])."</div>";

                $html .=  "<div class='email_address'>";
                
                

                if($hide_email_url){


                    $html .=  "<a href='".$hide_email_url."'>Kontakt os</a>";


                } else {


                    $html .=  $val["email"];


                }

                
                
                $html .=  "</div>";


            }



            $html .=  "</div>";


            return $html;

        }


        

        public static function formatPhoneNumber($number) {
            

            return substr($number, 0, 2).' '.substr($number, 2, 2).' '.substr($number, 4, 2).' '.substr($number, 6, 2);
        

        }


        public static function bank(){

            
            $company = Companies::get();

            return $company["bank"];

        }



        public static function get_all_emails($type = "all",$only_emails = false){

            
            $support_emails = array();

            $company = Companies::get();

            $all_emails = array();
            
            
            if(isset($company["email"]))
            foreach($company["email"] as $id => $arr){
        
                
                $add_email = false;
        

                $name = $arr["name"];
        
                $email = $arr["email"];
                
                $support = $arr["support"];

                $transactions = $arr["transactions"];

                $jobmail = $arr["jobmail"];
                
                
                

                if(in_array($email,$support_emails)){ continue; }
        
        
                // ONLY TAKE SUPPORT EMAILS IF TYPE IS SUPPORT
                if($type == "support" and $support){ $add_email = true; }
        
        
                // ONLY TAKE SUPPORT EMAILS IF TYPE IS SUPPORT
                if($type == "job" and $jobmail){ $add_email = true; }
        
                
                if($type == "transactions" and $transactions){ $add_email = true; }


                if($type == "all"){ $add_email = true; }

                
                
                if($add_email){ 
                    

                    if($only_emails){

                        $support_emails[$id] = $email;

                    } else {

                        $support_emails[$id]["name"] = $name;
                        $support_emails[$id]["email"] = $email;

                    }

    
                }



                if($only_emails){

                    $all_emails[$id] = $email;

                } else {

                    $all_emails[$id]["name"] = $name;
                    $all_emails[$id]["email"] = $email;

                }

                
                

            }
            

            if(empty($support_emails) and !empty($all_emails)){

                return $all_emails;

            } else {

                return $support_emails;

            }
            
        }


        public static function full_address(){


            $c = Companies::get();

            $val = $c["company"].", ".$c["address"];
            

            if(!empty($c["housenumber"])){

                $val .= " ".$c["housenumber"];

            }


            if(!empty($c["floor"])){

                $val .= " ".$c["floor"];

            }
            

            $val .= " - ".$c["zipcode"];

            $val .= " ".$c["city"];

            return $val;

        }


    }

?>