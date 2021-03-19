<?php

    namespace App\Models\Breadcrumbs\Breadcrumbs;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    class Breadcrumbs extends Model{



        public static function product($product_ref_id = 0){


            $link = [];



            if(!$product_ref_id){

                $product_ref_id = Shop::get_product_by_url();

            } 

            
            $category_ref_id = Format::current( Products::get_categories($product_ref_id) );

         
            $c = Format::current( Categories::get( ["categories" => [ $category_ref_id ] ] ) );

            
            $name = $c["name"];

            $url = $c["url"];

             
            $link[]= [
                "url"=>$url,
                "name"=>$name
            ];

          

            echo "<div class='breadcrumbs'>";


                echo "<a href='/'>Forside</a>";

                
                if(count($link) > 1){ echo " &#9656;"; }
                


                foreach($link as $val){
                    

                    $url = $val["url"];

                    $name = ucfirst(Format::strtolower($val["name"]));

                    echo "<a href='".$url."'>".$name."</a>";


                }
                

            echo "</div>";



        }



    }

?>