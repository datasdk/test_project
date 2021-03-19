<?php
    
    $block = 0;
    $groupes = array();

    $submenu_links = array();

    // LINKS

    if(!empty($submenu)){
        

        if(isset($submenu["categories"]))
        $submenu_links["categories"]= Tree::build($submenu["categories"]);

        
        if(isset($submenu["links"]))
        $submenu_links["links"]= Tree::build($submenu["links"]);
              

        
        foreach($submenu_links as $groupe_name => $groupe)
        foreach($groupe as $id => $arr){


            $block = $groupe_name;
            

            $baseUrl = "/";

            //if($groupe_name == "categories"){ $baseUrl = "/shop/"; }

            

            if(isset($arr["children"])){

                
                $block = $id;

                $groupes[$block][] = array("headline"=>true,"url"=>$arr["url"],"title"=>$arr["title"]);

                
                foreach($arr["children"] as $category_ref_id => $arr2){  

                    $groupes[$block][] = array("url"=>$arr2["url"],"title"=>$arr2["title"]);

                }



            } else {
                
            
                $groupes[$block][] = array("url"=>$arr["url"],"title"=>$arr["title"]);
                
            }
             


        }


    }


    // INSERT LINKS


    foreach($groupes as $arr){


        echo "<div class='link_groupe'>";


        foreach($arr as $val){

            $class = "";
            
            if(isset($val["headline"])){
                
                $class = "link_groupe_header";

            }
            
         

            $title = $val["title"];
            
            $link = $val["url"];


            echo "<a href='".$link."' class='".$class."'>".$title."</a>";

            
        }


        echo "</div>";


    }

    //$link = "/shop/".Shop::url_encode($arr3["title"]);
    //echo "<div class='link_groupe'>";
   // echo "</div>";


?>