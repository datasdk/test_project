<div class="comment-post">


    <?php

        if(Customer::getCustomerId()){
    ?>


        <form method="post" class="comment_form">
            

            <input type="hidden" name="comment_ref_id" class="comment-comment_ref_id" value="<?php echo $comment_ref_id ?>">


            <div class="comment-post-name">Kommentar</div>

            <textarea name="comment" class="comment-field" 
            maxlength="500" placeholder="Skriv en kommentar"></textarea>

            <button class="comment-btn">Slå op</button>

            <div class="word_count"><span class="words">0</span>/500</div>

        </form>



    <?php

        } else {

          //  echo "<i class=''>".Sentence::translate("Please login to write a comment")."</i>";

        }

    ?>

</div>