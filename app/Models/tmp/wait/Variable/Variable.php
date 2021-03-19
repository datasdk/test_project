<?php

    Class Variable{


        public static function get($name,$only_value = true){


            $sql = "select * from variables where name = '".$name."' ";
            
            $result = DB::select($sql);

          

            if(!$result){ return false; }


            if($only_value){


                $result = Format::current( $result );
            
                $type = $result["type"];

                $result = trim($result["value"]);


                if($type == "array"){

                    $result = json_decode($result,1);

                }

                
            }
            

            if($type == "integer"){
                
                $result = intval($result);

            }


            return $result;


        }


        public static function get_by_category($category){


            $sql = "select * from variables where category = '".$category."' ";
   
            $result = DB::select($sql);
            
            return $result;


        }

        public static function contains($name, $value = null, $category = false){


            $sql = "
            select id from variables 
            where 
            name = '".$name."'";
            

            if($value != null){

                $sql.= " and value = '".$value."' ";

            }


            if($category != false){

                $sql.= " and category = '".$category."' ";

            }
            

            return DB::numrows($sql);

            
        }
             


        public static function set($name,$value,$category = ""){


     
            $type = gettype($value);
            
                        

            if(!self::exists($name)){

                
                return self::add($name,$value,$category);


            } else {


                if($type == "array"){ $value = json_encode($value); }


                $sql = "
                update variables set 
                value = '".$value."', 
                type = '".$type."' 
                where 
                name = '".$name."'";

                return DB::update($sql);


            }


        }



        public static function add($name,$value,$category = ""){


     
            $type = gettype($value);
            
            if($type == "array"){ $value = json_encode($value); }


            $arr = array("category"=>$category,
                         "name"=>$name,
                         "value"=>$value,
                         "type"=>$type,
                         "created"=>time()
                        );


            return DB::insert("variables",$arr);

        }

        
    


        public static function exists($name){


            $sql = "
            select id from variables 
            where
            name = '".$name."' ";

            return DB::numrows($sql);

            
        }


        public static function delete($name){


            $sql = "
            delete from variables 
            where name = '".$name."'";

            DB::delete($sql);

            
        }


        public static function remove($name){

            return self::delete($name);

        }

    }

?>