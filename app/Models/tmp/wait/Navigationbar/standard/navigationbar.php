<?php

	$class = array();
	
	if($follow_screen){

		$class[]= "follow_screen";

	}

	if($logo){

		$class[]= "has_logo";
	
	}



	if($navigationbar){

?>

	



	<div id='obj-<?php echo $object_ref_id;?>' 
	class="navigationbar-wrapper navigationbar-<?php echo $name; ?> <?php echo implode(" ",$class); ?>">
		
		
		<div class="content">


			<?php

				if(isset($params["logo"]))
				if($params["logo"]){
				

					echo Logo::insert();

				}

			?>
			
			<?php
				
				if($content){

					echo $content;

				}

			?>

				<div  class='navigationsbar' data-closed='1'>
				

					<?php

	
						foreach($navigationbar as $id => $val){
								

								if(empty($val["name"])){ continue; }
								
								$new_window = $val["new_window"];
									
								//$lang = Language::get_code_by_language_id();

								$link = $val["link"];


								$name = ucfirst(trim($val["name"]));


								if(empty($name)){ continue; }
								
								if(empty($link)){ $link = "#"; }


	
								
								$class = "";
										
								if($link == $_SERVER["REQUEST_URI"]){ $class = "active"; }

									
								echo '<a href="'.$link.'" class="main" '.$class.'" data-id="'.$id.'"';
										
										
								if($new_window){

									echo "target='_blank'";

								}

								echo ">";
										
										

								if(isset($val["icon"]))
								if($val["icon"]){

									echo "<i class='fas fa-".$val["icon"]."'></i>";

								}


								echo $name;		

										
								echo "</a>";


								if(!empty($val["children"])){

									echo "<div class='dropdown' data-id='".$id."'><i class='fas fa-sort-down'></i></div>";

								}


									// SUBMENU
									
						
								if(isset($val["children"])){

									
									$submenu = $val["children"];
										
										
								
									echo "<ul class='submenu_".$id." submenu submenu_navigation'>";


										include(__DIR__."/../includes/links.php");


									echo "</ul>";

										
								}
							
						

						}
						
					?>  


				</div>


		</div>
		

	</div>


<?php

	}

?>



