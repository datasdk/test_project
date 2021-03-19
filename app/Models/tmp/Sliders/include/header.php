<?php
	

	if(!self::where("object_ref_id",$object_ref_id)->count()){
		

		self::create([
			"object_ref_id"=>$object_ref_id,
			"date"=>time(),
			"visible"=>1
		]);

			
	}
	
	
	
	$shuffle = false;
	$type = 0;
	

	$sql = "select * from images";
	$images = DB::select($sql);

	
	$slides = array();
	
	
	$sql = "
	select *,
	sliders_slides.id as slider_id
	from sliders
	
	inner join sliders_slides
	on sliders.id = sliders_slides.slider_ref_id

	where 
	sliders.object_ref_id = '".$object_ref_id."' and
	sliders.visible = 1
	
	order by sliders_slides.sorting desc
	";


	$result = DB::select($sql);
	


	if(!empty($result)){
		
		
		$slide = 0;
	
		foreach($result as $val){
			

			if(empty($val->active)){ continue; }
			
			
			$slider_ref_id = $val->slider_ref_id;
			$shuffle = $val->shuffle;
			
			$type = $val->type;
						
			
			$slides[$slide]["background_image_ref_id"] = $val->background_image_ref_id;
			$slides[$slide]["background_phone_image_ref_id"] = $val->background_phone_image_ref_id;
			

			$slides[$slide]["title"]      = $val->title;
			$slides[$slide]["text"]       = $val->text;
			$slides[$slide]["active"]     = $val->active;
			$slides[$slide]["link_text"]  = $val->link_text;
			$slides[$slide]["align"]  	  = $val->align;


			$link = $val->link;
			
			
										
			if (strpos($link , 'flybox:') !== false) {	
				
				$link = str_replace("flybox:","",$link);
				
				$link = "javascript:flybox('".$link."')";
				
				
			}
			
			
			
			$slides[$slide]["link"] = $link;
	
	
			$slide++;
			
						
		}
	
	}
	
	
	$antal_slides = count($slides);
	
	
	if($shuffle){ shuffle($slides); }


?>