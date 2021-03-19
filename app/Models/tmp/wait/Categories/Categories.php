<?php

    namespace App\Models\Categories;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

        
    Class Categories extends Model{

        
        public static $all_categories = false;
        public static $all_category_urls = array();



        public static function get($options = []){


            $id = false;
            $categories = "*";
            $page = false;
            $order_by = false;
            $rules = false;


            extract($options);


            if(!$categories){ $categories = "*";  }
    

            $result = Shop::load_inventory($options);
            
         
            
            $return = array();
            
            $parent_ids = array();

            $i = 0;
            


            if(isset($result["categories"]))
            foreach($result["categories"] as $categories_ref_id => $val){


                if($id)
                if(!in_array($categories_ref_id,$id)){

                    continue;


                }
           
                // FILTER SHOWCASE

                if($rules){


                    if(in_array("showcase",$rules)){ 
                        
                        if(!$val["showcase"]){ continue; } 
                    
                    }


                }
                
                
     
               
                // Filter all categories there not match the parent or meets the requirements

                if($categories !== "*"){

                    if(!is_array($categories)){ $categories = array($categories); }

                    if(!in_array($categories_ref_id,$categories) and !in_array($val["parent_id"],$parent_ids)){

                        continue;
    
                    }

                }
                
        
                // hent alle parent ids med i resultatet, som ligger under denne varegruppe
                $parent_ids[]= $val["id"];
            


    
                // end filter
               
                $return[$categories_ref_id] = $val;


            }
            

            return $return;

        }



        public static function exists($categories_ref_id){

            
            if(empty($categories_ref_id)){ return false; }


            $sql = "select id from categories where id = '".$categories_ref_id."'";

            $numrows = DB::numrows($sql);


            if($numrows){ return true; }


            return false;


        }



        public static function add($name){
      
            
            $name = Sentence::set($name);


            $arr = [
                    "name"=>$name,
                    "for_delivery"=>1,
                    "for_pickup"=>1,
                    "always_variable"=>1,
                    "active"=>1,
                    ];

      

            $categories_ref_id = DB::insert("categories",$arr);

            return $categories_ref_id;

        }



        public static function get_id_by_name($name){


            $name = Format::strtolower($name);
            

            $sql = "
            select id from categories 
            where LOWER(name) = '".$name."'";

            $res = DB::select($sql);


            if(empty($res)){ return false; }


            $res = Format::current($res);
            

            if(!isset($res["id"])){

                return false;

            }


            $id = $res["id"];


            return $id;


        }


        
        public static function get_all_categories($props = []){


            $parent_id = false;

            extract($props);



         //   if(empty(self::$all_categories)){



                $sql = "
                select *, 
                categories.id as id
                from categories 

                inner join categories_url
                on categories.id = categories_url.category_ref_id

                where 

                categories.active = 1 
                ";


                if($parent_id !== false){

                    $sql .= " 
                    and 
                    categories.parent_id = '".$parent_id."' ";

                }


                $sql .= "
                order by 
                
                categories.parent_id
                ";

           

                $cat = [];

                $res = DB::select($sql);


                foreach($res as $id => $val){

                    $res[$id]["name"] = Sentence::get($val["name"]);
                    $res[$id]["url"] = "/shop".$val["url"];

                }


                self::$all_categories = $res;

          //  }
            

            return self::$all_categories;

        }


        
        public static function available($category_ref_id){

    
            $result = self::get_all_categories();



            if(empty($result[$category_ref_id])){
                
                return false;

            }


            $category = $result[$category_ref_id];

     

            $for_delivery    = $category["for_delivery"];
            $for_pickup      = $category["for_pickup"];
            $always_variable = $category["always_variable"];
            

            $day[1] = $category["monday"];
            $day[2] = $category["tuesday"];
            $day[3] = $category["wednesday"];
            $day[4] = $category["thursday"];
            $day[5] = $category["friday"];
            $day[6] = $category["saturday"];
            $day[7] = $category["sunday"];


            $from_hours   = $category["from_hours"];
            $from_minutes = $category["from_minutes"];
            $to_hours     = $category["to_hours"];
            $to_minutes   = $category["to_minutes"];


            $available = true;

            $now = time();

            $today = date("N");

            $from = strtotime($from_hours.":".$from_minutes);

            $to = strtotime($to_hours.":".$to_minutes);

         
            
            if(!$always_variable){
            
              
                // not variable today

                if(!$day[$today]){
                 
                    $available = false;

                }


                // if out of interval
                
            
                if($now < $from or $now > $to){
              
                    $available = false;

                }


            }
           
            
      

            return $available;
    

        }



        public static function getCategoryByName( $name_array ){
 
            // MIS
            if(!is_array($name_array)){ $name_array = array($name_array); }


            $categories = DB::select("select * from categories order by parent_id");

            $parent_id = 0;
            $level = 0;
            $id = 0;


            foreach($categories as $val){


                $name = Shop::url_encode($val["name"]);


                if($val["parent_id"] == $parent_id or !$parent_id)
                if(isset($name_array[$level]))
                if($name  == $name_array[$level])
                if(1){



                    $parent_id = $val["id"];
                    $level ++;

                    if($level == count($name_array)){

                        return $val["id"];

                    }

                }

            }


            return false;

        }
        


        public static function getCategoryByUrl( $url = 0 ){

            
            if(empty(self::$all_category_urls)){ self::load(); }
         

            $url = self::$all_category_urls;


            $shop_category_url = Shop::$shop_category_url; 


            foreach($url as $category_ref_id => $arr){


                if(array_search($shop_category_url,$arr)){ 
        
                    return $category_ref_id;
                
                }

            }

           

            return false;
          
        }


        public static function get_childrens($category_ref_id){


            $sql = "
            select * from categories 
            where parent_id = '".$category_ref_id."'";


            $result = DB::select($sql);


            if(empty($result)){ return false; }


            $children_id = array();


            foreach($result as $arr){

                $children_id[]= $arr["id"];

            }


            return $children_id;

        }



        public static function get_product_amount($categories){


            if(!is_array($categories)){

                $categories = array($categories);

            }


            $sql = "
            select * from products_categories 
            where 
            category_ref_id = ".implode(" OR category_ref_id = ",$categories);


            $numrows = DB::numrows($sql);


            return $numrows;


        }



        public static function overview($arr = []){


            $items_pr_row = 4;
            $button_text = false;
            $baseurl = false;

            extract($arr);


            
            ob_start();


                $categories = self::get();


                $sql = "
                select * from categories 
                where 
                active = 1 and 
                showcase = 1
                order by parent_id";

                $result = DB::select($sql);
                
                
                if(empty($result)){ return false; }
                
           

                $categories = array_chunk($result,$items_pr_row);



                echo "<div class='category_showcase'>";


                    foreach($categories as $arr){


                        echo "<div class='row'>";


                            foreach($arr as $val){


                                $url = self::get_category_url($val["id"],0, $baseurl);

                                $image = Cloudi::get($val["image_ref_id"]);



                                echo "<div class='col'>";


                                    echo "<div class='category_showcase_item'>";


                                        echo "<a href='".$url."'>";
                                        
                                            echo "<div class='image' style='background-image:url(".$image.")'></div>";
                                        
                                        echo "</a>";



                                        echo "<div class='info-container'>";

                                            echo "<h4 class='showcase_title'>".ucfirst(Sentence::get($val["name"]))."</h4>";
                                                
                                            echo "<p class='showcase_description'>".ucfirst(Sentence::get($val["description"]))."</p>";
                                            

                                            if($button_text){

                                                echo "<a href='".$url."' class='showcase_submit'>".$button_text."</a>";

                                            }
                                            

                                        echo "</div>";

                                    
                                    echo "</div>";


                                echo "</div>";


                            }

                    
                        echo "</div>";

                    }
                    

                    


                echo "</div>";



            $content = ob_get_contents();
            
            ob_end_clean();


            return $content;

        }



        public static function get_category_url($category_ref_id, $language_ref_id = 0, $baseurl = false){



            if(!$baseurl){ $baseurl = "/shop"; }
            


            if(!$language_ref_id){

                $language_ref_id = Language::get();

            }

        

            if(empty(self::$all_category_urls)){ self::load(); }
         


            $url = self::$all_category_urls;

       
            
            if(isset($url[$category_ref_id][$language_ref_id])){


                $return = Languages::lang_url();
                
             

                return $return .  $baseurl . $url[$category_ref_id][$language_ref_id];

            }


            return false;


        }



        public static function get_category_by_url($url){
            


            $url = "/".$url;

            $url = str_replace("//","/",$url);


            $sql = "
            select category_ref_id 
            from categories_url 
            where url = '".$url."'";


            $res = Format::current( DB::select($sql) );


            if(!$res){ return false; }


            $category_ref_id = $res["category_ref_id"];


            return $category_ref_id;


        }



    }

?>