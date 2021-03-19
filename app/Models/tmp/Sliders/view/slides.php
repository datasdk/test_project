
<div class="slider_wrapper slider_type_<?php echo $type; ?>" data-total-slides="<?php echo count($slides); ?>">


	<ul class="slider">

	
	<?php

		$slides = array_reverse($slides);
					
		$index = 0;


		foreach($slides as $slider_id => $val){
				


			$title = ucfirst(nl2br(Sentence::get($val["title"])));
			$text = ucfirst(nl2br(Sentence::get($val["text"])));
			$background_image_ref_id = $val["background_image_ref_id"];
			$background_phone_image_ref_id = $val["background_phone_image_ref_id"];
			$link = $val["link"];
			$link_text = trim(Sentence::get($val["link_text"]));
			$active = $val["active"];
			$align = $val["align"];
				

			$Logo = Logo::insert();


			$background = "";
			$image = "";
			$style = "";


			$index++;


			if(isset($images[$background_image_ref_id])){ 
				
				$background = Cloudi::get($background_image_ref_id,1920,false,75); 
				
			}
		

			if(isset($images[$background_phone_image_ref_id])){ 
				
				$background_phone = Cloudi::get($background_phone_image_ref_id,800,false);  
			
			}


			if(empty($background_phone)){

				$background_phone = $background;

			}


		
			echo "
			<li class='slide slide-".$index." background background_standard' 
			>";


				// screen
			
	

				echo "
				<div class='background background_standard align-".$align."' 
				style='background:url(".$background.")'></div>";

				// phone

		
				//echo "<div class='background background_xs align-".$align."' style='background:url(".$background_phone.")'></div>";

			


				// bg

				echo "<div class='slider-overlay'></div>";


					echo "<div class = 'text_wrapper'>";


						if($title){ echo "<h1 class='slider-title'>".$title."</h1>"; }
								
						if($text){ echo "<p class='slider-description'>".nl2br($text)."</p>"; }
								
								
						if(!empty($link)){
									
							if(empty($link_text)){ $link_text = "Læs mere" ;}
							
							// if link is ALL CATEGORIES - change to /shop/
							if($link === "*"){ $link = "/shop"; }

							echo "<a href='".$link."' class='slider_link'>".$link_text."</a>";

						}
						

					echo "</div>";


					
				echo "</li>";
				

		}

	?>

	</ul>

	<?php

		if($stamp){

			//echo "<div class='slider_stamp'>".Image::get("slider_".$object_ref_id."_stamp","img")."</div>";

		}
	
	?>

</div>


