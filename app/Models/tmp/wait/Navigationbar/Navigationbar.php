<?php

	namespace App\Models\Navigationbar;


	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	use App\Models\Tree\Tree;

	use DB;
	use Components;
	use Languages;
	use Articles;
	use Sentence;
	use Customer;
	

	class Navigationbar extends Model{
	
		
		use HasFactory;


		public static $navigationbar;
		public static $navigationbar_name;
		public static $categories;
		public static $pages;
		public static $phonebar_is_loaded = false;


		public static function loads($object_ref_id = 0){
			


			$mysqli = DB::table('settings_navigationbar');
			


			if(empty(self::$navigationbar)){


				include(__DIR__."/includes/header.php");

				self::$navigationbar = $navigationbar;
				
			}




			if($object_ref_id == 0){

				return self::$navigationbar;

			}
			


			if(empty(self::$navigationbar[$object_ref_id])){

				return false;

			}
			
		
			$nav = self::$navigationbar[$object_ref_id];
			
			

			return $nav;

		}


		public static function create($name){

			
			$object_ref_id = Components::set($name, 'navigationsbar');	

			return $object_ref_id;

		}
		
		public static function insert($arr = []){
			
			
			
			$object_ref_id = 0;
			$name = "";
			$logo = false;
			$follow_screen = false;
			$content = false;


			$params = Components::get_parameter($object_ref_id);

			
			extract($arr);



			if(!$object_ref_id){ return false; }
			


			ob_start();

			
			
				$navigationbar = self::where("object_ref_id",$object_ref_id)->get();
		

				$navigationbar = Tree::build($navigationbar);

				

				include(__DIR__."/standard/navigationbar.php");


				$content =  ob_get_contents();



			ob_end_clean();

			


			return $content;
			

		}

		
		
		public static function phone(){

		

			if(self::$phonebar_is_loaded == true){ return false; }

			self::$phonebar_is_loaded = true;


			$object_ref_id = self::create("phone_menu");


			$navigationbar = self::loads($object_ref_id);

			
	
			include(__DIR__."/includes/top_bar.php");
			

			include(__DIR__."/mini/mini.php");
			

		}


		public static function cleanUrl($val){


			$first_letter = substr($val,0,1);

			//$val = str_replace("//","/",$val);
			
			if($val == "" or $val == "index"){

				$val = "/";

			}


			if($first_letter != "/"){

				//$val = "/".$val;

			}

			return $val;


		}


		public static function add_submenu_link($name,$url){

			return "<li><a href='".$url."'>".ucfirst($name)."</a></li>";  
	
		}


		public static function categories($name,$url){
		

			if(empty(self::$categories)){


				$sql = "
				select * from settings_navigationbar_submenu_categories
				
				inner join categories on
				categories.id = settings_navigationbar_submenu_categories.category_ref_id
			
				inner join categories_url on
				categories.id = categories_url.category_ref_id
				";


				$result = DB::select($sql);

				foreach ($result as $val){

					

				}


				self::$categories;


			}

		}


		public static function pages($name,$url){


			$sql = "select * from settings_navigationbar_submenu_pages";

			$result = DB::select($sql);


			foreach ($result as $val){

					

			}


		}


		// INSERTS


		public static function insert_categories($category_ref_id = 0){


			
//"no_products",
			$navigations_categories = Categories::get_all_categories(["parent_id"=>0]);



			$r = [];

			foreach($navigations_categories as $category_ref_id => $val){


				$navibar_id = ($category_ref_id);
			
				
				$url = $val->url;


				$r[$navibar_id]["icon"] = "";
				$r[$navibar_id]["name"] = $val->name;
				$r[$navibar_id]["link"] = $url;
				$r[$navibar_id]["new_window"] = $val->new_window;
				$r[$navibar_id]["sorting"]  = 0;
				$r[$navibar_id]["show_in_phonemenu"] = 1;		


				if(isset($val->children)){

					$r[$navibar_id]["submenu"] = $val->children;	

				}
				
			}


			return $r;

		}



		


		public static function insert_link($link_id){

			
			if(empty($link_id)){ return false; }


			$sql = "select * from settings_navigationbar where id = '".$link_id."'";

			$result = DB::select($sql);

			
			if(empty($result)){ return false; }



			foreach($result as $id =>  $val){
				

				$lang_url = Languages::lang_url();


				if($val->link == "[open_booking]"){

					$link = "javascript: booking_popup()";		
					
					$lang_url = "";

				} 

				else

				if($val->link == "[open_login]"){

					$link = "javascript:open_login()";		
					
					$lang_url = "";

				} 

				else

				if($val->link == "[open_signup]"){

					$link = "javascript:open_signup()";		
					
					$lang_url = "";

				} 

				else
				
				if(strpos($val->link, '[article') !== false) { 					

					$article_ref_id = str_replace(["[article_","]"],"",$val->link);

					$link = Article::get_url_by_id($article_ref_id);
		
					// byg videre herfra
					$link = $lang_url.$link;	

				} 
				
				else 
				
				{

					$link = $lang_url.Navigationbar::cleanUrl($val->link);

				}


				//$link = str_replace("//","/",$link);


				if(empty($link)){ $link = "#"; }
			

				$r[$id]["icon"] = $val->icon;
				$r[$id]["name"] = Sentence::get($val->name);
				$r[$id]["parent_id"] = $val->parent_id;
				$r[$id]["link"] = $link;
				$r[$id]["new_window"] = $val->new_window;
				$r[$id]["sorting"]  = $val->sorting;
				$r[$id]["show_in_phonemenu"] = 1;

				

				if($val->has_submenu){

					$sm = self::insert_submenu($id);
					
					if($sm){

						$r[$id]["submenu"] = $sm;

					}
					
					
				}
				


			}

			return $r;

		}


		

		public static function insert_article($article_id){
	

			if(empty($article_id)){ return false; }


			$language_ref_id = Language::get();


			$sql = "
			select * from articles
			
			inner join articles_url
			on articles.id = articles_url.article_ref_id
			
			where 
			articles.id = '".$article_id."' and 
			articles_url.language_ref_id = '".$language_ref_id."'
			
			";

	

			$result = DB::select($sql);


			$r = [];


			foreach($result as $id => $val){


				$link = "/article/".$val->url;


				$r[$id]["icon"] = "";
				$r[$id]["name"] = Sentence::get($val->short_title);
				$r[$id]["link"] = $link;
				$r[$id]["new_window"] = $val->new_window;
				$r[$id]["sorting"]  = 0;
				$r[$id]["show_in_phonemenu"] = 1;

			}
			


			//$r[$id]["submenu"] = self::insert_submenu($id);
			

			return $r;

		}


		public static function insert_submenu($link_id){
			
			
			$return = [];


			// insert categories

			$sql = "
			select * from  settings_navigationbar_submenu_categories 
			where navigation_ref_id = '".$link_id."'";


			$result1 = DB::select($sql);


			$c = [];

			foreach($result1 as $val){

				$l = $val->category_ref_id;
				
				if($c){ $c[]= $l; }
				
			}


			if(!empty($c)){

				$return["categories"] = self::insert_categories($c);

			}
			


			// insert pages

			$sql = "select * from  settings_navigationbar_submenu_pages where navigation_ref_id = '".$link_id."'";

			$result2 = DB::select($sql);



			$p = [];

			foreach($result2 as $val){

				$l = self::insert_link($val->id);

				if($l){ $p[]= $l; }
				

			}

			if(!empty($p)){

				$return["pages"] = Format::current($p);

			}
			

			// insert articles

			$sql = "
			select * from  settings_navigationbar_submenu_articles 
			where navigation_ref_id = '".$link_id."'";


			$result3 = DB::select($sql);
			


			$a = [];

			foreach($result3 as $val){

				$l = self::insert_article($val->article_ref_id);

				if($l){	$a[]= $l; }
				

			}


			
			if(!empty($a)){

				$return["articles"] = Format::current($a);

			}
			

			if(empty($return)){ return false; }

			
			return $return;
			
				
				
		}

	}	


?>