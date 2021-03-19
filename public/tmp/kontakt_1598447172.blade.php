<?php

    include(__DIR__."/header.php");
    
    $categories = Categories::get_all_categories();

?>


<div class="page-wrapper">





        <div class="content2">


            <?php

                echo Frontend::slider("index");
            
            ?>


            <div class="contact-container">

                <?php

                    echo Frontend::contact("contact");
                
                ?>

            </div>


            <div class="contact-info">

                <div class="pb-4">

                    <?php

                        echo Google_maps::insert(["height"=>250]);
                    
                    ?>
                
                </div>


                <div class="pb-4">

                    <h4>Adresse</h4>
                    
                    <?php

                        echo "<div>".$info["company"]."</div>";
                        echo "<div>".$info["address"]."</div>";
                        echo "<div>".$info["zipcode"]." ".$info["city"]."</div>";
                        echo "<div>Cvr: ".$info["cvr"]."</div>";
                    
                    ?>

                </div>

                <div class="pb-4">

                     <h4>Kontakt os</h4>

                    <?php

                        echo Company::phone();

                        echo Company::email();

                    ?>       

                </div>


            </div>





    </div>


   

</div>

<?php

    include(__DIR__."/footer.php");

?>