<?php
    
    class Shop {
        
        public static $current_url;
        public static $inventory;
        public static $parent;
        public static $return_parents;
        public static $encode = array();
        public static $product_url = array();
        public static $shop_index = 0;
        public static $indexes = 0;
        public static $amount = 0;
        public static $current_page = 1;
        public static $images = array();
        public static $products = array();
        public static $variants = array();
        public static $variant_categories = array();
        public static $category_url = array();
        public static $current_search_word = array();
        public static $current_product_url = "";
        public static $shop_category_url = "";
        public static $category_top_category = null;

        

        public static function load_inventory($options = []){

            
            $categories = 0; 
            $page = 0;
            $order_by = false; 
            $variants = false;
            $min_price = false; 
            $max_price = false;
            $search = false;
            $rules = false;

    
            $mysqli = DB::mysqli();
                
            $images = Cloudi::get();
        
            $inventory = array();
        
            $images_in_shop =  Layout::get("images_in_shop");
        
            $filtermenu = array();
        
            $limit = 100;
        
            $language_ref_id = Language::get();

        
            $prices["min"] = 0;
            
            $prices["max"] = 0;

            $products = "*";


            $no_products = false;
        
            $level_0 = false;
        
            $showcase = false;


           
            extract($options);

            

            $limit_start_point = $limit * $page;

       



            if($rules){

                $no_products    = in_array("no_products",$rules);
                $level_0        = in_array("level_0",$rules);
                $showcase       = in_array("showcase",$rules);

            }
            



            $sql = "
            select *, 
        
            categories.id as categories_id,
            categories.name as categories_name,
            categories.parent_id as category_parent_id,
        
            categories.name as categories_name,
            categories.description as categories_description,

            categories.visible as categories_visible,

            categories.image_ref_id as category_image_ref_id

            ";


            
            if(!$no_products){

                // category seperateor
                $sql .= ",";

                $sql .= "
                products.id as product_id,
                products.name as product_name,
                products.description as product_description,
                products.no_delete as product_no_delete,
                products.visible as product_visible,

            
                products_prices.id as prices_id,
                products_prices.product_ref_id as prices_product_ref_id,
                products_prices.variant_ref_id as prices_variant_ref_id,
                products_prices.price as price,
                products_prices.relative as prices_relative,
                products_prices.type as price_type
            
                ";

            }

            
            $sql .= "

            from categories

            ";
           

            if(!$no_products){


                $sql.= "

                inner join products_categories
                on products_categories.category_ref_id = categories.id

                inner join products
                on products_categories.product_ref_id = products.id
            
                inner join products_prices
                on products_prices.product_ref_id = products.id

                ";
            
    
            
            // images skal ikke ind her, da produkter uden billede også 
            // skal vises, og left join ikke virker til denne sql
            
            
                $where = array();
                
                
                if($categories != "*")
                if($categories != 0){

                  
                   
                    $all_categories = Products::getParents($categories);

                    if($all_categories){

                        $where[]= "( categories.id = ".implode(" OR categories.id = ",$all_categories).")"; 
                        
                    }
                    

                }
            
            
            
                if(is_array($variants)){
                    
                    $where[]= "( products_prices.variant_ref_id = ".implode(" OR products_prices.variant_ref_id = ",$variants).")";
            
                }
            
                
                // only show showcase
                if($showcase){

                    $where[]= "( categories.showcase = 1)";
            
                }



                if($min_price != 0 and $max_price != 0){ 

                    $where[] = " ( products_prices.price >= ".intval($min_price)." and products_prices.price <= ".intval($max_price).") ";
            
                }
                

                $where[] = "( products.visible = 1 )";

        

                $search = Search::get_product_id_by_search();

                

                if($search){

                    $products = $search;

                } else if(Search::is_search()){

                    return false;

                }


                if($products != "*")
                if(is_array($products)){

                    if(!empty($products)){

                        $where[]= "( products.id = ".implode(" OR products.id = ",$products).")";

                    }
                    

                }


            }
            

            if($level_0){

                $where[]= "( categories.parent_id = 0)";

            }


            $where[]= "( products.active = 1)";
            
            

            if(!empty($where)){
        
                $sql .= " where ".implode(" AND ",$where);
        
            }
        
        
            $sql .= " order by ";

            $sql .= "
            categories.parent_id,
            categories.sorting
            ";

            
        
            if($order_by){

                //separator
     
                
                if($order_by = "relevant"){         $sql .= ", products.id "; }
        
                if($order_by = "price_high_low"){   $sql .= ", products_prices.price "; }
        
                if($order_by = "price_low_high"){   $sql .= ", products_prices.price desc "; }
        
                if($order_by = "name"){             $sql .= ", products.name "; }
                
        
            }
        
        
        

            if(!$no_products){


                $sql .= "
                ,
                ABS(products.item_number),
                products.sorting,
                products.id desc,
                products_prices.id
                
                ";


            }
        
    

           // $total_products = DB::numrows($sql);

            if(!$no_products){
                
               // $sql .= " limit ".$limit_start_point.",".$limit." ";
            
            }


            
            $sql_key = sha1($sql);

            
            // IF SQL EXISTS LOAD

            if(isset(self::$inventory[$sql_key])){ 

                return self::$inventory[$sql_key];

            }

           


            $result = mysqli_query($mysqli,$sql);

            

        
            // variant_categories
            if(empty(self::$variant_categories)){
                
                $sql = "select * from variant_categories";
                self::$variant_categories = DB::select($sql);

            }

            

            // variant_categories
            if(empty(self::$images)){
                
                $sql = "select * from products_images";
                $output = DB::select($sql);

                $images = array();

                foreach($output as $id => $val){

                    $images[$val["product_ref_id"]][$id] = Image::get($val["image_ref_id"]);

                }


                self::$images = $images;

            }


            // variants
            if(empty(self::$variants)){
                
                $sql = "select * from variants";
                self::$variants = DB::select($sql);

            }


        
            $i = 0;

           
        
        
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                
                
                $category_image_ref_id = $row["category_image_ref_id"];
                

               


                $url = Categories::get_category_url($row["categories_id"]);


        
                $inventory["categories"][$row["categories_id"]]["id"] = $row["categories_id"];
                $inventory["categories"][$row["categories_id"]]["parent_id"] = $row["category_parent_id"];
                $inventory["categories"][$row["categories_id"]]["url"] = $url;
                $inventory["categories"][$row["categories_id"]]["name"] = Sentence::get($row["categories_name"]);
                $inventory["categories"][$row["categories_id"]]["description"] = Sentence::get($row["categories_description"]);
                $inventory["categories"][$row["categories_id"]]["image_ref_id"] = $category_image_ref_id;
                $inventory["categories"][$row["categories_id"]]["showcase"] = $row["showcase"]; 
                $inventory["categories"][$row["categories_id"]]["discount"] = 0;
        
                       
                $inventory["categories"][$row["categories_id"]]["for_delivery"]     = $row["for_delivery"];
                $inventory["categories"][$row["categories_id"]]["for_pickup"]       = $row["for_pickup"];
                $inventory["categories"][$row["categories_id"]]["always_variable"]  = $row["always_variable"];
                $inventory["categories"][$row["categories_id"]]["monday"]           = $row["monday"];
                $inventory["categories"][$row["categories_id"]]["tuesday"]          = $row["tuesday"];
                $inventory["categories"][$row["categories_id"]]["wednesday"]        = $row["wednesday"];
                $inventory["categories"][$row["categories_id"]]["thursday"]         = $row["thursday"];
                $inventory["categories"][$row["categories_id"]]["friday"]           = $row["friday"];
                $inventory["categories"][$row["categories_id"]]["saturday"]         = $row["saturday"];
                $inventory["categories"][$row["categories_id"]]["sunday"]           = $row["sunday"];
                $inventory["categories"][$row["categories_id"]]["from_hours"]       = $row["from_hours"];
                $inventory["categories"][$row["categories_id"]]["from_minutes"]     = $row["from_minutes"];
                $inventory["categories"][$row["categories_id"]]["to_hours"]         = $row["to_hours"];
                $inventory["categories"][$row["categories_id"]]["to_minutes"]       = $row["to_minutes"];

                $inventory["categories"][$row["categories_id"]]["visible"]          = $row["categories_visible"];
                
                $inventory["categories"][$row["categories_id"]]["total_products"] = 0;
                
             

                if(!$no_products)
                if($row["product_ref_id"]){
                    
        
                    $url = self::get_product_url($row["product_ref_id"],$language_ref_id);
                    
 

                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["url"] = $url;
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["id"] = $row["product_id"];

                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["name"] = Sentence::get($row["product_name"]);
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["description"] = Sentence::get($row["product_description"]);

                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["item_number"] = $row["item_number"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["category_ref_id"] = $row["categories_id"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["modified"] = $row["modified"];
        
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["is_book_able"] = $row["is_book_able"];

                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["tags"] = $row["tags"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["date"] = $row["date"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["active"] = $row["active"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["discount"] = Discount::get($row["product_id"]);;
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["pick_able"] = $row["pick_able"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["categories"][$row["categories_id"]] = $row["categories_id"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["no_delete"] = $row["product_no_delete"];
        
                    
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["min"] = $row["min"];
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["max"] = $row["max"];

              
                    $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["visible"] = $row["product_visible"];
                    

                
                    if(isset(self::$images[$row["product_id"]])){
                        

                        $inventory["categories"][$row["categories_id"]]["products"]
                        [$row["product_id"]]["image"] = self::get_main_image($row["product_id"]);

                        
                        $inventory["categories"][$row["categories_id"]]["products"]
                        [$row["product_id"]]["images"] = self::get_all_images($row["product_id"]);
        

                    }
                

                    // #valuta

                    $valuta_ref_id = $row["valuta_ref_id"];

                    if(!$valuta_ref_id){ $valuta_ref_id  = Valuta::get_default_valuta(); }
                    

                  
        
                    if($row["prices_variant_ref_id"]){
        

                        $variant_categories = self::$variant_categories;

                        $variants = self::$variants[$row["prices_variant_ref_id"]];

                        $variant_category_ref_id = $variants["variant_category_ref_id"];
                        
                        $variant_category_name = $variant_categories[$variant_category_ref_id]["name"];

                        

                        $variant_name = $variants["name"];

                        $sorting = $variants["sorting"];


                  
                    
                        $inventory["filter"][$row["categories_id"]][$variant_category_ref_id]["variant_category_name"] = $variant_category_name;
                        $inventory["filter"][$row["categories_id"]][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["variant_name"] = $variant_name;
                        $inventory["filter"][$row["categories_id"]][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["products"][$row["product_id"]] = 1;
                        $inventory["filter"][$row["categories_id"]][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["sorting"] = $sorting;
                        
                      


                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["name"] = $variant_category_name;

                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["name"] = $variant_name;
                        
                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["price"][$valuta_ref_id] = $row["price"];

                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["type"] = $row["price_type"];
                        
                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["variants"][$row["prices_variant_ref_id"]]["sorting"] = $sorting;


        
                        if($row["image_ref_id"]){

        
                            $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["prices"]["category"][$variant_category_ref_id]["variants"][$row["variant_ref_id"]]["images"][$row["image_ref_id"]] = $row["image_ref_id"];
                        
                        }
        

        
                    } else {
                    
                        
                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["price"][$valuta_ref_id]["price"] = $row["price"];
                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["price_type"] = $row["price_type"];
     
                    }
        
                    
        
                    if($prices["min"] > $row["price"] or !$prices["min"]){ $prices["min"] = $row["price"]; }
                    if($prices["max"] < $row["price"] or !$prices["max"]){ $prices["max"] = $row["price"]; }
        
                    

                    $inventory["categories"][$row["categories_id"]]["min_price"] = $prices["min"];
                    $inventory["categories"][$row["categories_id"]]["max_price"] = $prices["max"];


                    
                    if(!empty($row["products_accessories_product_ref_id"])){
                
                        $inventory["categories"][$row["categories_id"]]["products"][$row["product_id"]]["children"][$row["products_accessories_product_ref_id"]] = 1;
                
                    }



       

                  

                   // self::$products[$row["product_id"]] = $products;

        
                }
                

            }


            self::$inventory[$sql_key] = $inventory;


            


            return $inventory;


        }


        public static function get_main_image($product_id,$variants = []){

            // billeder med flere varianter skal laves $variants

            $i = current( self::$images[$product_id] );
            
            return $i;

        }


        public static function get_all_images($product_id){

            return self::$images[$product_id];

        }

       
        public static function offer($options = []){



            extract($options);


            $options["type"] = "offer";
        

            return self::insert($options);


        }

        
        
        public static function insert($options = []){


         
            $main_category_ref_id = 0;
            $categories = 0;
            $products = "*";
            $page = 0; 
            $order_by = false;
            $variants = false; 
            $min_price = false; 
            $max_price = false;
            $search = false; 
            $has_filter = true;
            $in_groupes = true;
            $has_banner = true;
            $is_search = false;
            $has_product_prevew = true;
            $type = "list";
            $callback = false;
            $price_text = "Pris";
            $amount_text = "Antal";
            $has_amount = true;
            $custom_cart_function = false;

 
            extract($options);
       
            

            if(!$search){ 

               // $search = Search::get_product_id_by_search();

            }


            if(!$categories){ 

                
                $main_category_ref_id = Categories::getCategoryByUrl();

                $categories = [$main_category_ref_id ];
         
                if(empty($categories)){ $categories = []; }
                
                $options["categories"] = $categories;

            }


            

            $result = array();
            

            // if $category_ref_id = 0, dont show
            // if $category_ref_id is * show all

            
           
            $inventory = self::load_inventory($options);


        
          

            if(isset($inventory["categories"])){
        
                $result = $inventory["categories"];
        
            }
            

 

            $total_products = 0;
        
            $no_products = true;
        
            $stock = Stock::get();

        
    
        
            $layout = Layout::get("shoplist");
        
            $layout_shop_cart = 1;
        
            $enable_shop = Settings::get("enable_shop");
        
        
            
            if($type == "list"){
                
                include(__DIR__."/includes/shoplist/shop-list.php");

            }
           
            
            if($type == "offer"){

                include(__DIR__."/includes/offer/offer.php");

            }
            

            if($type == "table"){

                include(__DIR__."/includes/table/table.php");

            }


            if($type == "booking"){


                include(__DIR__."/includes/booking/booking.php");

                
            }
           
        
        }


   



        public static function product_preview($option = []){


            $product_ref_id = 0;
            $related_products = true;
            $show_stock = true;
            $layout = "webshop";
            $accessorie_ref_id = 0;
            $add_to_cart_button= true;
            $has_form = true;
            $has_image = true;
            
            
            extract($option);



            echo "<div class='product_preview_wrapper'>";
                    

                include(__DIR__."/includes/product_preview/product_preview.php");
            
                    
                if($related_products){

                    self::related_products($product_ref_id);

                }
            

            echo "</div>";


        }



        public static function url_encode($val){

            $val = trim($val);

            $val = Format::strtolower(trim($val));

            $val = str_replace(" ","-",$val);

            $val = preg_replace('/[^a-åA-Å0-9-_\.]/','', $val);

            return $val;
 
        }
 

        public static function get_product_by_url($url = 0){

            
            $url = Shop::$current_product_url;


            $sql = "select * from products_url where url = '".$url."'";

            $result = DB::select($sql);


            if(!$result){ return false; }

            $id = current($result)["product_ref_id"];

            return $id;


        }



        public static function set_last_order_id($order_ref_id){

            if(!empty($order_ref_id)){
                
                Session::set("last_order_ref_id",$order_ref_id);

            }
            

        }


        public static function get_last_order_id(){

            
            if(!empty(Session::get("last_order_ref_id"))){

                return Session::get("last_order_ref_id");

            }   
            
            return false;

        }


        public static function showcase($options = []){
    
            $title = false;
            $description = false;
            $limit = 20;
            $order_by = false;
            $scroll_menu = false;


            extract($options);


            return include(__DIR__."/includes/showcase/showcase.php");
            

        }


        public static function related_products($product_ref_id = 0,$limit = 5,$headline=true){

            //$product_refference = 0 = random

            if(!$product_ref_id){

                $product_ref_id = Shop::get_product_by_url();

            }


            include(__DIR__."/includes/related_products/related_products.php");
 

        }



        public static function render_products($arr = [],$params = []){
            
            $has_product_prevew = true;
            $only_show_empty_stock = false;
            $has_amount = true;
            $custom_cart_function = false;
            $has_product_prevew = true;
            $custom_cart_text = false;
            $type = "list";
            $highligth_products = false;
            $highligth_class = false;
            

            extract($arr);

            extract($params);
            
            
            if(empty($products)){ return false; }


            $layout = layout::get("shoplist");
        
            $layout_shop_cart = 1;
        
            $enable_shop = Settings::get("enable_shop");

            
            include(__DIR__."/includes/shoplist/products.php");


            
            
        }



        public static function insert_free_delivery(){


            $delivery_free_delivery_active = 
            Settings::get("delivery_free_delivery_active");


            $delivery_free_delivery_over_amount = 
            Settings::get("delivery_free_delivery_over_amount");


            if($delivery_free_delivery_active){

                echo '<div class="free_delivery pt-3 block">'.Sentence::translate("Free shipping on purchase over").' '.$delivery_free_delivery_over_amount.' kr.</div>';

            }


        }


        public static function validate_vat($total){


            $vat_included = Settings::get("vat_included");


            if(!$vat_included){

                $vat = $total / 100 * 25;
        
                $subtotal = ($total + $vat);
        
        
            } else {
        
                $subtotal = ($total);
        
            }
            

            return $subtotal;

        } 


 
        public static function pager($categories){




        }


       


        public static function get_product_url($product_ref_id,$language_ref_id){


            if(empty(self::$product_url)){
                
                
                $product_url = array();

            
                $sql = "select * from products_url";
            
                $result = DB::select($sql);


                foreach($result as $val){


                    
                    $url = Languages::lang_url();
                    
                    $url .= "/product/" . $val["url"];

                    $product_url[$val["product_ref_id"]][$val["language_ref_id"]] = $url;

                }

              
                self::$product_url = $product_url;

            }
            


            $product_url = self::$product_url;
        


            if(isset($product_url[$product_ref_id][$language_ref_id])){

                return $product_url[$product_ref_id][$language_ref_id];

            }
            

            return false;

        }



    }




?>