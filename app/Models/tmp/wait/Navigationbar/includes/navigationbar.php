<?php

	$class = "";

	if($follow_screen){

		$class = "follow_screen";

	}



	if($navigationbar){


?>


	
	<div class="navigationbar_space"></div>



	<div  class="navigationbar-wrapper <?php echo $subject;?> <?php echo $class; ?>">

	

		<div class = "container">


			<div class='navigationsbar' data-closed='1'>
			

				<?php

					
					if(isset($navigationbar[$object_ref_id])){


						$navigationbar = $navigationbar[$object_ref_id]["page"];

		
					

						foreach($navigationbar as $id => $arr){
							

							$val = $arr["links"];


							$class = "";

							if(isset($arr["submenu"])){ $class = "has_submenu"; }



							echo "<span>";


								echo "
								<a href='".$val["url"]."' 
								class='main ".$class."' data-id='".$id."'>";
								
								
									if($val["icon"]){

										echo "<i class='fas fa-".$val["icon"]."'></i>";

									}


									echo ucfirst($val["name"]);	
									
								
								echo "</a>";


								if(!empty($arr["submenu"]["categories"])){

									echo "
									<div class='dropdown' data-id='".$id."'>
									<i class='fas fa-sort-down'></i>
									</div>";

								}


							echo "</span>";


							// SUBMENU
							
						
							if(isset($arr["submenu"])){
								

								$submenu = $arr["submenu"];
								
								
							
								echo "<div class='submenu_".$id." submenu submenu_navigation'>";
									
									
									echo "<div class='submenu_container'>";


										echo "<div class='link_wrapper no_scrollbar'>";

										

											include(__DIR__."/includes/links.php");



										echo "</div>";
									

									echo "</div>";

									
								echo "</div>";
							
							
							}
								
								
						}


					}

					
				?>  

			</div>

		</div>

	</div>


<?php

	}

?>



