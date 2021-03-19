<?php

    namespace App\Models\Tree;


    Class Tree{


        public static $level = 0;

        	
        public static function  build( $ar, $pid = 0  ) {
                

            if(empty($ar)){ return false; }
            
            
            $op = array();
            
            
            foreach( $ar as $item ) {
                
             
                if(empty($item['id'])){  continue; }
                
                if(!isset($item['level'])){ $item['level'] = 0; }	
                
                
                if( $item['parent_id'] == $pid ) {
                                
                    $op[$item['id']] = $item;
                                        
                    // using recursion
                    $children =  self::build( $ar, $item['id'] );

                    
                    if( $children ) {
                    
                        $op[$item['id']]['children'] = $children;
                        
                    }
                    
                }
                
                
                
            }
            
            return $op;
            
        }



        public static function expand($arr , $start = 0 , $enter_child = 0 , $leave_child = 0 ,$params = false){
        

            if(empty($arr)){ return false; }

            
            $op = array();
                
            
            foreach($arr as $item ) {
                
                
                if(empty($item['id'])){ continue; }

                        
                if(isset($item["children"])){

                    $item["level"] = self::$level;
                    
                    if(!empty($enter_child)){ 
                    
                        $enter_child($item,$params); 

                    }

                    self::$level++;

                    self::expand( $item["children"] , $start , $enter_child , $leave_child,$params);
                    
                    
                } else {
                    
                    $item["level"] = self::$level;

                    if(!empty($start)){ 
                    
                        $start($item,$params); 
                    
                    }			   
                    
                }		
                
                
                if( !next( $arr ) ) {
                    
                    $item["level"] = self::$level;

                    if(!empty($leave_child)){ 
                        
                        

                        $leave_child($item,$params); 

                    }	
                    
                    if(self::$level > 0){

                        self::$level--;

                    }
                    
                    
                }  		
                

            }

            
        }




    }


?>