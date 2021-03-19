<?php

    Class Sitemap{


        public static function load(){

            $sql = "select * from frontend_sitemap";

            $result = DB::select($sql);

            return $result;

        }

        public static function get($links_pr_row = 5){


            $sitemap = self::load();


            echo "<div class='sitemap'>";


                echo "<div class='content'>";


                    $i = 0;


                    foreach($sitemap as $val){
                        
                        
                        $i ++;


                        if($i == 1){

                            echo "<ul>";

                        }
                        

                            
                        echo "<li><a href='".$val["url"]."'>".ucfirst($val["title"])."</a></li>";




                        if($i >= 5){

                            $i = 0;

                            echo "</ul>";   

                        }


                    


                    }
                
                
                    
                echo "</div>";

            
            echo "</div>";


        }
        
        

        public static function create_sitemap_xml(){

         

            $filename = "sitemap.xml";
            $filetime = 0;


        
            
            if(DB::is_localhost()){
            

                if(file_exists($filename)){ unlink($filename); }


            } 

            else

            {


                $host = "https://".$_SERVER["HTTP_HOST"];


                $sitemap = '<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';


                // PAGES

                $pages = Page::get();
                

                foreach($pages as $val){


                    if(!$val["visible"]){ continue; }


                    $url = $host.$val["url"];

                    $modified = $val["modified"];

                    $changefreq = "WEEKLY";

                    $priority = strval($val["priority"]);
                    

                    $sitemap .= self::add_sitemap_row($url,$changefreq,$modified,$priority);


                }


                // languages

                $languages = Language::load();


                // PRODUCTER

                
                
                $categories = Categories::get();

                $shop_cart = 1;

            

           


                foreach($categories as $arr){


                    if(!$arr["visible"]){ continue; }


                    $url = $host.$arr["url"];
                    
                    $modified = time();

                    $changefreq = "WEEKLY";
                    
                    $priority = "0.8";
                    

                    $sitemap .= self::add_sitemap_row($url,$changefreq,$modified,$priority);



                    if(empty($arr["products"]) or !$shop_cart){ continue; }            



                    foreach($arr["products"] as $arr2){
                        
                        
                        if(!$arr2["visible"]){ continue; }


                        $url = $host.$arr2["url"];
                        
                        $modified = $arr2["modified"];

                        $changefreq = "WEEKLY";
                            
                        $priority = "0.8";
                            
            
                        $sitemap .= self::add_sitemap_row($url,$changefreq,$modified,$priority);


                    }
                    

                }

                $sitemap .= '</urlset>';
                

         
            
                
                file_put_contents($filename,trim($sitemap));


                // update sitemap

                $sql = "update frontend_sitemap set refresh = 0";

                DB::update($sql);


                // sitemap

                

            } 

            
            $n = DB::numrows("select id from frontend_sitemap");

            if(!$n){
                
                DB::insert("frontend_sitemap",["refresh"=>0]);
                
            }

    
    }



    private static function add_sitemap_row($url,$changefreq,$lastmod,$priority){
                

        if(empty($lastmod)){ $lastmod = time();  }
    
        if(empty($changefreq)){ $changefreq = "monthly"; }


        return '
        <url>
            <loc>'.$url.'</loc>
            <lastmod>'.date("c",$lastmod).'</lastmod>
            <changefreq>'.$changefreq.'</changefreq>
            <priority>'.$priority.'</priority>
        </url>
        ';
    
    }


}



?>