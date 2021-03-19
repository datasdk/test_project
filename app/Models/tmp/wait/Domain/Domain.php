<?php

    namespace App\Models\Domain\Domain;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;




    class Domain extends Model{


        public static function set($domain = 0, $type = "frontend"){


            if(!$domain){

                $domain = $_SERVER["HTTP_HOST"];

            }


            if(!DB::numrows("select * from domain")){

                $arr = ["domain" => $domain];
        
                DB::insert("domain",$arr);
        
            } else {
                        
        
               // DB::update("update domain set domain = '".$domain."'");
        
            }


        }

        public static function get($type = "frontend"){


            if(DB::is_localhost()){


                if($type == "frontend"){

                    $url = "http://local.web.datas.dk";

                }

                if($type == "admin"){
                   
                    $url = "http://local.admin.datas.dk";

                }
                
                
                return $url;


            } else {

                
                $sql = "select * from domain";


                if($type == "frontend"){

                    $sql .= " where type = 'frontend'";

                }

                if($type == "admin"){

                    $sql .= " where type = 'admin'";

                }

                $r = Format::current( DB::select($sql) );


                if($type == "admin"){
                
                    if(empty($r)){ 
                        
                        return "https://admin.".$_SERVER["HTTP_HOST"]; 
                    
                    }

                } 


                return "https://".$r["domain"];

        

            }
            

          

        }

    }

?>