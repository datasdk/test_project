<?php

	namespace App\Models\Sliders;


	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;


	use Logo;


	
	Class Sliders extends Model{

		
		use HasFactory;


		public static function insert($arr =[]){

	
			
			$stamp = false;

			$object_ref_id = false;

			extract($arr);

	

			if(!$object_ref_id){ return false; }

	
			ob_start();

		
			
				include(__DIR__."/include/header.php");
					

					
				if($antal_slides > 0){
					
					include(__DIR__."/view/slides.php");
		
				}


				$content =  ob_get_contents();



			ob_end_clean();


			return $content;


		}


	}


?>