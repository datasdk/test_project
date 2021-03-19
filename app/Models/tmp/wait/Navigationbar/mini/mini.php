<?php

	if($navigationbar){

?>


<div  class='navigationbar-mini' data-closed='1'>
			

	<?php
				
							
		foreach($navigationbar as $id => $val){


			//	echo '<div class="header"></div>';

			if(empty($val["name"])){ continue; }

			$new_window = $val["new_window"];
								
			$link = $val["link"];



			if(isset($val["show_in_phonemenu"])){

				$show_in_phonemenu = $val["show_in_phonemenu"];

				if(!$show_in_phonemenu){ continue; }

			}
					

					
			echo "<span>";


				echo "<a href='".$link."' class='main' data-id='".$id."'";
											
					if($new_window){

						echo "target='_blank'";

					}

				echo ">";
									
									
				if(isset($val["icon"]))
				if($val["icon"]){

					echo "<i class='fas fa-".$val["icon"]."'></i>";

				}


				echo ucfirst($val["name"]);		
								
							
				echo "</a>";


				
				if(!empty($val["children"])){

					echo "<div class='dropdown' data-id='".$id."'><i class='fas fa-sort-down'></i></div>";

				}


			echo "</span>";


			// SUBMENU
								
					
			if(isset($val["children"])){
									

				$submenu = $val["children"];
									
										
				echo "<div class='submenu_".$id." submenu'>";
										
										
					echo "<div class='submenu_container'>";


						echo "<div class='link_wrapper no_scrollbar'>";

											
							include(__DIR__."/../includes/links.php");


						echo "</div>";
										

					echo "</div>";

										
				echo "</div>";
								
								
			}
									
	
		}

					
	?>  

</div>


<?php

	}

?>



