<?php

    namespace App\Models\Contact;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    Class Contact extends Model{


        public static function insert($props){


           echo "
           <Contact 
           action  = '".$props["action"]."'
           company = '".$props["company"]."'
           >
           </Contact>";

        }


    }

?>