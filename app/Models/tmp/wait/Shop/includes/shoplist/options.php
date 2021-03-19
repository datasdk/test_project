<?php



    $options["order_by"]["label"] = "Sorter efter"; 
    $options["order_by"]["option"][] = array("label"=>"Relevans","value"=>"relevant","selected"=>true);
    $options["order_by"]["option"][] = array("label"=>"Pris stigende","value"=>"price_low_high","selected"=>false);
    $options["order_by"]["option"][] = array("label"=>"Pris faldende","value"=>"price_high_low","selected"=>false);
    $options["order_by"]["option"][] = array("label"=>"Name","value"=>"name","selected"=>false);

    // LIMIT

    $options["limit"]["label"] = "Vis"; 
    $options["limit"]["option"][] = array("label"=>"25 stk","value"=>"25","selected"=>false);
    $options["limit"]["option"][] = array("label"=>"50 stk","value"=>"50","selected"=>true);
    $options["limit"]["option"][] = array("label"=>"100 stk","value"=>"100","selected"=>false);
    $options["limit"]["option"][] = array("label"=>"250 stk","value"=>"250","selected"=>false);


?>


<div class="options_wrapper">


    <form class="shop_list_form" onsubmit="return shoplist_option_submit(this)">


        <input type="hidden" name="category_ref_id" value="<?php echo $category_ref_id; ?>" readonly>



        <!-- SORTING -->

        <?php foreach($options as $name => $op1):?>


            <label>

                <span>

                    <?php 

                        echo $op1["label"].":";

                    ?>

                </span>

                <select name="<?php echo $name; ?>">

                    <?php foreach($op1["option"] as $op2): ?>
                        
                        <?php

                            $sel = "";

                            if($op2["selected"]){ $sel = "selected"; }
                        
                        ?>

                        <option value="<?php echo $op2["value"]; ?>" <?php echo $sel; ?>><?php echo $op2["label"];; ?></option>

                    <?php endforeach; ?>

                </select>

            </label>



        <?php endforeach; ?>


    </form>


</div>