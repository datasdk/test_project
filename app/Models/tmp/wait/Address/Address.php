<?php

    namespace App\Models\Address\Address;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    


    Class Address extends Model{

        use HasFactory;

        public static function insert(){


            ob_start();
            

                echo Company::info();


                $content =  ob_get_contents();


            ob_end_clean();


            return $content;


        }

    }

?>