<?php
    
    
    $s = Sentence::where("language_ref_id",1)->get()->pluck('content','group_id');

    return $s->toArray();

?>