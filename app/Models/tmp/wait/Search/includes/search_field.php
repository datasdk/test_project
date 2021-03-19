<div class="search_wrapper">

    <?php

        $url =  Languages::lang_url();

    ?>

    <form method="get" onsubmit="return search_submit('<?php echo $url; ?>/search/products',this)">


        <div class="search_content">
                    
            
            <input type="text" name="search" value="" placeholder="<?php echo Sentence::translate("Search products");?>" required> 
                    
            <button><i class="fas fa-search"></i></button>


        </div>


    </form>

</div>

