<?php


    if(!$category_ref_id){

        $booking_category_name = Booking::$booking_category_name;

        $category_ref_id = Categories::get_category_by_url( $booking_category_name );



    }
    

    $default_timestamp_start = time();

    $default_timestamp_end = time();


    $end_booking["min_timestamp"] = 0;
    $end_booking["max_timestamp"] = 0;

    $product_ref_id = 0;

    $s = Booking::get_booking_settings($product_ref_id);
    

    $processing_time_days = Settings::get("processing_time_days");
    $processing_time_minutes = Settings::get("processing_time_minutes");
    
    $min_periode_days = 0;
    $min_periode_days_minutes = 0;
    $max_periode_days = 0;
    $max_periode_days_minutes = 0;


    if($s["has_min_period"])
    if($s["min_periode_days"]){

        $min_periode_days = $s["min_periode_days"];
        $min_periode_days_minutes = $s["min_periode_days_minutes"];

        $end_booking["min_timestamp"] = 
        strtotime("midnight +".$min_periode_days." days +".$min_periode_days_minutes." minutes");

    }


    if($s["has_max_period"])
    if($s["max_periode_days"]){

        $max_periode_days = $s["max_periode_days"];
        $max_periode_days_minutes = $s["max_periode_days_minutes"];

        $end_booking["max_timestamp"] = 
        strtotime("midnight +".$max_periode_days." days +".$max_periode_days_minutes." minutes");

    }
    
   

    if($o){

        if($o["booking_start"]){    $default_timestamp_start = $o["booking_start"]; }
    
        if($o["booking_end"]){      $default_timestamp_end   = $o["booking_end"]; }

    }
  

    if(empty($category_ref_id)){

        echo "<div class='alert alert-primary'>Der er ingen ledige under denne kategori</div>";

    } else {

?>


    <div class="booking-shop-wrapper"
    data-category_ref_id="<?php echo $category_ref_id; ?>"

    data-min_periode_days="<?php echo $min_periode_days; ?>"
    data-min_periode_days_minutes="<?php echo $min_periode_days_minutes; ?>"
    data-max_periode_days="<?php echo $max_periode_days; ?>"
    data-max_periode_days_minutes="<?php echo $max_periode_days_minutes; ?>"
    >



    <form id="booking_time" method="post" onsubmit="return booking_get_products()">


        <div class="booking-title">
                        
            <?php echo Text::get("chose_car_title"); ?>

        </div>



        <div class="booking-settings">


            <div class="booking-option">
                

                <strong class="booking-label"><?php echo Sentence::translate("Pickup date");?></strong>
                                                
                            
                <?php

                    $arr = ["name"=>"booking_start",
                            "type"=>"pickup",
                            "timepicker"=>1,
                            "default_timestamp"=>$default_timestamp_start,
                            "time_type"=>"select",
                            "next_day_if_empty"=>true,
                            "timepicker"=>false,
                            "onchange"=>"booking_start_change",
                            "onload"=>"booking_datepicker_onloaded"
                            ];

                    Datepicker::insert($arr);

                ?>



            </div>


            <div class = "booking-option">


                <strong class="booking-label"><?php echo Sentence::translate("Return date");?></strong>


                <?php
                    
   
                    $arr = 
                    [
                    "name"=>"booking_end",
                    "type"=>"delivery",
                    "timepicker"=>1,
                    "default_timestamp"=>$default_timestamp_end,
                    "time_type"=>"select",
                    "next_day_if_empty"=>true,
                    "timepicker"=>false,
                    "onchange"=>"booking_end_change",
                    "onload"=>"booking_datepicker_onloaded"
                    ];
                        
                    Datepicker::insert($arr);

                ?>

            </div>

            
            <?php 
                
                if(!$auto_update){

                    echo "<button class='booking-submit'> <i class='fas fa-search'></i> Søg</button>";

                }
            
            ?>
            

            

        </div>



        <div id="booking_shop_wrapper">
                
            <?php Image::loader() ?>

            <div class="content"></div>
                        
            <a href="/" class="btn btn-default"><?php echo Sentence::translate("Back");?></a>

        </div>



    </form>

    </div>

<?php

    }

?>
