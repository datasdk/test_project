<?php

	$languages = Languages::get();

	$language_code = Languages::get_code_by_language_id();
	
	$articles = Articles::loads();


	// get top categories

			//$top_categories = Tree::build($result);



	$navigationbar = array();

	$categories = array();
	

	$sql = "
	select *,
	settings_navigationbar.id as link_id,
	settings_navigationbar.name as navi_name


	from Components
	

	inner join settings_navigationbar on 
	settings_navigationbar.object_ref_id = Components.id
	
	where 

	Components.type = 'navigationsbar'

	and settings_navigationbar.active = 1 


	order by 

	Components.sorting,
	settings_navigationbar.sorting
	";


	
	$result = DB::select($sql);

	foreach ($result as $row){
		


		$object_id = $row->object_ref_id; // dont change
		
		$link_id = $row->link_id;

		$has_submenu = $row->has_submenu;

		$only_online = $row->only_online;

		$only_offline = $row->only_offline;


		
		if($only_online or $only_offline){


			if(Customer::is_logged_in()){


				if($only_offline){ continue; }


			} else {


				if($only_online){ continue; }


			}


		}

		
		

		$n = false;

		
		if($row->link === "*"){

			
			$n = Navigationbar::insert_categories();
		
			$type = "category";


		} else {


			$n = Navigationbar::insert_link($link_id);

			$type = "page";
	

		}



	

		if($n)
		foreach($n as $id => $val){


			$link = $val["link"];

			$link = str_replace('"',"'",$link);


			$navigationbar[$object_id][$id]["id"] = $id;
			$navigationbar[$object_id][$id]["icon"] = $val["icon"];
			$navigationbar[$object_id][$id]["name"] = $val["name"];
			$navigationbar[$object_id][$id]["parent_id"] = $val["parent_id"];
			$navigationbar[$object_id][$id]["link"] = $link;
			$navigationbar[$object_id][$id]["new_window"] = $val["new_window"];
			$navigationbar[$object_id][$id]["sorting"] = $val["sorting"];
			$navigationbar[$object_id][$id]["show_in_phonemenu"] = $val["show_in_phonemenu"];

		}

		
		
	
	}

	

?>