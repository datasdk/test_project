<?php
	
	$navigationbar = array();
	$categories = array();
	

	$language_code = Language::get_code_by_language_id();


	$sql = "
	select *,
	settings_navigationbar.id as navibar_id,
	
	settings_navigationbar.id as id,
	settings_navigationbar.name as navi_name,
	settings_navigationbar.link as link,
	settings_navigationbar.new_window as new_window,

	
	categories.id as category_id,
	categories.parent_id as category_parent_id,
	categories.name as category_name,
	categories.parent_id as category_parent_id

	
	from settings_navigationbar
	
	inner join Components on 
	settings_navigationbar.object_ref_id = Components.id
	
	left join settings_navigationbar_submenu_categories on 
	settings_navigationbar_submenu_categories.navigation_ref_id = settings_navigationbar.id

	left join categories on
	categories.id = settings_navigationbar_submenu_categories.category_ref_id

	left join settings_navigationbar_submenu_pages on 
	settings_navigationbar_submenu_pages.navigation_ref_id = settings_navigationbar.id

	where settings_navigationbar.active = 1 
	";



	if($subject != "*"){

		$sql .= " and Components.name = '".$subject."'";

	}
	

	$sql .= "
	order by 
	
	settings_navigationbar.sorting,
	settings_navigationbar_submenu_categories.sorting,
	settings_navigationbar_submenu_pages.sorting,
	categories.name
	";


	
	$result = mysqli_query($mysqli,$sql);

	while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
		

		$object_ref_id = $row["object_ref_id"];

		$link = "/" . $language_code . "/" . navigationbar::cleanUrl($row["link"]);
		
	

		$navigationbar[$row["navibar_id"]]["links"]["object_ref_id"] = $row["object_ref_id"];
		$navigationbar[$row["navibar_id"]]["links"]["icon"] = $row["icon"];
		$navigationbar[$row["navibar_id"]]["links"]["name"] = $row["navi_name"];
		$navigationbar[$row["navibar_id"]]["links"]["link"] = $link;
		$navigationbar[$row["navibar_id"]]["links"]["new_window"] = $row["new_window"];
		$navigationbar[$row["navibar_id"]]["links"]["sorting"] = $row["sorting"];

		

		// SUBMENU

		if($row["category_ref_id"]){
			

			$url = 0;
			
			$parent_id = $row["parent_id"];



			$navigationbar[$row["navibar_id"]]["submenu"]["categories"][$row["category_ref_id"]] = 
			array(
			"id" => $row["category_id"], 
			"url" => $url, 
			"parent_id" => $row["category_parent_id"], 
			"title" => ucfirst($row["category_name"])
			);
	

		}
		
		

		if($row["page_ref_id"]){


			$page = Page::get($row["page_ref_id"]);

			if(isset($page["parent_id"])){

				$parent_id = $page["parent_id"];

				$link = navigationbar::cleanUrl($page["url"]);
	
				$navigationbar[$row["navibar_id"]]["submenu"]["links"][$row["page_ref_id"]] = $page;

			}

		}
		

	}

	
?>