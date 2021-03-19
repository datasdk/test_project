<?php

    namespace App\Models\Api\Api;

    use Dompdf\Dompdf;

    class Pdf{

        

        public static function create($html,$dir_filename){

                    
            $dompdf = new Dompdf();
                
       
            include(__DIR__."/includes/standard/standard.php");


            $dompdf->loadHtml($html);
        
            $dompdf->setPaper('A4', 'portrait');
                
            $dompdf->render();
        
            file_put_contents($dir_filename,$dompdf->output());
        
            return $dir_filename;

        }


        public static function invoice($order_ref_id = 0){


            if(empty($order_ref_id)){

                $order_ref_id = 
                Order::get_order_id();

              
            }

            $path = "";

            include(__DIR__."/includes/invoice/invoice.php");


            return $path;

            
        }
		
	}

?>