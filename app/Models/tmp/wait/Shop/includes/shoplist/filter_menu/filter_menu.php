<div class="filter_menu">

    <?php


        $categories = "*";

        if(isset($_GET["categories"])){

            $categories = $_GET["categories"];

        }
                    


        $category_list = Shopmenu::category_list($categories);


        if($category_list){

            echo $category_list ;

        }
        

        echo Shopmenu::filter($categories);

                
    ?>

</div>
