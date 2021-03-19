<?php
	
	namespace App\Models\Information;


	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	use App\Models\Cloudinary\Cloudinary;
	

	use DB;
	use Components;


	class Information extends Model{
	
	
		use HasFactory;
		


		public static $result;


		public static function insert($props = []){

			
			$object_ref_id = false;
			$type = false;
			
	
			$icon = 0;

			$contain = 0;
			$cover = 0;
			
			$top = 0;
			$bottom = 0;
			$size = 0;
			$pos = 0;


			extract($props);


			if(!$object_ref_id){ return false; }

		


			$sql = "
			select * from information  
			where 
			active = 1 and 
			object_ref_id = '".$object_ref_id."' and 
			public_date <= ".time()."
			order by sorting";
			
			$result = DB::select($sql);


	
			$post_collums = Components::get_parameter($object_ref_id, "post_collums");
			
			if(!$post_collums){ $post_collums = 1; }

			
			include(__DIR__."/view/standard.php");	
							
				

			
		}


		public static function custom($result = []){

			
			if(empty($result)){ return false; }
  

			$object_ref_id  = 0;
			$post_collums = 4;
			$image_cover = 1; 


            if($image_cover){ $image_class = "cover"; } else { $image_class = "contain"; }


			include(__DIR__."/view/standard.php");	


		}

	}

?>