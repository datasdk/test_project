<div class="account_title">
        <h3>Tilknyt betalingskort</h3>
        <p></p>
    </div>


<div class="acccount_content">

    
    <p>
    For at opnå hurtigere og lettere betaling, når du handler på siden, bedes du tilknytte dit betalingskort. 
    Kortet bliver gemt sikkert og kryptere hos vores system, og vidergives ikke til en trejdepart.
    Har du yderligere spørgsmål til håndtering af betalinger, er du velkommen til at kontakte os.
    </p>


    <div class="pt-5"></div>


    <?php

        Stripe::insert(["type"=>"save_card","has_cart"=>false,"has_terms"=>false]);
    
    ?>

</div>