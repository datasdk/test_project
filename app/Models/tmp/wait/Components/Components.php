<?php
	
	namespace App\Models\Components;

	

	class Components extends Model{

		
		use HasFactory;


		public static function text($name,$params = []){
	
			$object_ref_id = Components::set($name,"text");
			
			return self::insert($object_ref_id,$params);

		}


		public static function articles($name,$params = []){

			$object_ref_id = Components::set($name,"article_group");

            return self::insert($object_ref_id,$params);

		}


		public static function article($name,$params = []){
			
			return self::articles($name,$params = []);

		}


		public static function slider($name,$params = []){

			$object_ref_id = Components::set($name,"slider");

			return self::insert($object_ref_id,$params);

		}


	

		public static function contact($name,$params = []){

			
			$object_ref_id   = Components::set($name,"contact");

			$params["formular_name"] = $name;


			return self::insert($object_ref_id,$params);

		}


		public static function image($name,$params = []){

			$object_ref_id = Components::set($name,"image");

            return self::insert($object_ref_id,$params);

		}


		public static function information($name,$params = []){

			$object_ref_id = Components::set($name,"information");

            return self::insert($object_ref_id,$params);

		}


		public static function google_maps($name,$params = []){

			$object_ref_id = Components::set($name,"google_maps");

            return self::insert($object_ref_id,$params);

		}


		public static function navigationbar($name,$params = []){

			
			$object_ref_id = Components::set($name,"navigationsbar");
			
			return self::insert($object_ref_id,$params);
			

		}


		public static function review($name,$params = []){

			$object_ref_id = Components::set($name,"review");

            return self::insert($object_ref_id,$params);

		}


		public static function parallax($name,$params = []){

			$object_ref_id = Components::set($name,"parallax");

            return self::insert($object_ref_id,$params);

		}


		public static function html($name,$params = []){

			$object_ref_id = Components::set($name,"html");

            return self::insert($object_ref_id,$params);

		}


		public static function login($name,$params = []){

			$object_ref_id = Components::set($name,"login");

            return self::insert($object_ref_id,$params);

		}


		public static function signup($name,$params = []){

			$object_ref_id = Components::set($name,"signup");

			$params["formular_name"] = $name;
			
            return self::insert($object_ref_id,$params);

		}


		public static function booking($name,$params = []){

            $object_ref_id = Components::set($name,"booking");

            return self::insert($object_ref_id,$params);

		}


		public static function gallery($name,$params = []){

            $object_ref_id = Components::set($name,"gallery");

            return self::insert($object_ref_id,$params);

		}



		public static function address($params = []){

            return  Address::insert($params);

		}

		public static function contact_info($params = []){

            return  Contact::insert_info($params);

		}


		public static function opening_hours($params = []){

            return  Opening_hours::insert($params);

		}


		public static function some($params = []){

            return  Socialmedia::insert($params);

		}



		
		public static function set($name, $type,$params = []){
			
	

			
			if(is_array($name)){

				sa($name);
				sa($type);

				return false;

			}
			
			if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $name)){

				return false;

			}


			if(empty($name)){

				return false;

			}
		

			// OPRET Components OBJEKT
			
		
			$result = 
			Components::where("name",$name)
			->where("type",$type)
			->where("active",1)
			->first();

			


			if(empty($result)){
				
		
				$object_ref_id = Components::create([
					"type"=>$type,
					"name"=>$name,
					"active"=>1
				]);
				
				
			} else {
				
				$object_ref_id = $result->id;			
				
			}



			if(!empty($params)){

				foreach($params as $name => $value){

					self::set_parameter($object_ref_id, $name, $value);

				}
				

			}
			

			return 	$object_ref_id;	
		
		}
		


		public static function get($object_ref_id){



			$result = self::where("object_ref_id",$object_ref_id)->first();

			
			if(empty($result)){ return false; }

			
			$result["params"] = self::get_parameter($result->id);


			return $result;

		}


		public static function set_parameter($object_ref_id, $name,$value = ""){


			$sql = "
			select * from components_objects_settings 
			where object_ref_id = '".$object_ref_id."' and name='".$name."'";

			$result = DB::numrows($sql);


			if($result){ 


				$sql = "
				update components_objects_settings 
				set
				value = '".$value."'
				where object_ref_id = '".$object_ref_id."' and name='".$name."'";

				DB::update($sql);


			} else {

				$arr = array("object_ref_id"=>$object_ref_id,
							 "name"=>$name,
							 "value"=>$value
			   				);

   				DB::insert("components_objects_settings",$arr);

			}


		}



		public static function get_parameter($object_ref_id, $name = 0){


			$sql = "
			select * from components_objects_settings 
			where object_ref_id = '".$object_ref_id."'";
			

			if($name){

				$sql .= " and name = '".$name."'";				

			}
			
			


			$result = DB::select($sql);


			if(empty($result)){ return array(); }


			if(!$name){
				
				$r = [];

				foreach($result as $val){

					$r[ $val->name ] = $val->value;

				}

				return $r;
				
			}


			return current($result)->value;


		}


		public static function insert($object_ref_id,$params = []){


			$p = 0;

			$pt = 0;
			$pr = 0;
			$pb = 0;
			$pl = 0;
			$m = 0;

            
			$object_ref_id = intval($object_ref_id);
	
			if(empty($object_ref_id)){ return false; }


			extract($params);
			
			  
			$r = self::find($object_ref_id);
	
			if(empty($r)){ return false; }

			
			
			$option = ["object_ref_id"=>$r->id];

			
			if(!empty($params)){

				$option = array_merge($option,$params);

			}



			ob_start();
	

		
				$type = $r["type"];
				$plugin_id = $r["plugin_ref_id"];
				$name = $r["name"];
		
		
				echo "
				<div id='".$name."' 
				data-object_ref_id = '".$object_ref_id."' 
				data-plugin_id = '".$plugin_id."'
				data-type = '".$type."'
				class= ' 
				".$type."-object 
				cl-obj 
				";


					if($p){  echo " p-".$p." "; }

					if($pt){ echo " pt-".$pt." "; }

					if($pr){ echo " pr-".$pr." "; }

					if($pb){ echo " pb-".$pb." "; }

					if($pl){ echo " pl-".$pl." "; }

					if($m === "auto"){ echo " m-auto "; }
					
       		

				echo "'>";
		

				
					if($type == "text"){
			
						echo Text::insert($option);
			
					}
			

					if($type == "article_group"){
			
						echo Articles::insert($option);
			
					}
			
			
					if($type == "slider"){
			
						echo Slider::insert($option);
			
					}
			
			
					if($type == "formular"){
			
						echo Formular::insert($option);
			
					}
			
			
					if($type == "contact"){
	
						echo Contact::insert($option);
			
					}
			
							
					if($type == "image"){
			
						echo Image::insert($option);
			
					}
			
			
					if($type == "information"){
			
						echo Information::insert($option);
			
					}
			
			
					if($type == "google_maps"){
			
						echo Google_maps::insert($option);
			
					}
			

					if($type == "navigationsbar"){
							
						echo Navigationbar::insert($option);
			
					}
			
						
					if($type == "review"){
							
						echo Reviews::insert($option);
			
					}


					if($type == "parallax"){
						
						echo Parallax::insert($option);
			
					}
			
			
					if($type == "html"){
						
						echo Html::insert($option);
			
					}


					if($type == "gallery"){
						
						echo Gallery::insert($option);
			
					}
			
			
					if($type == "login"){
						
						echo Login::insert($option);
			
					}


					if($type == "signup"){
					
						echo Signup::insert($option);
			
					}

					if($type == "booking"){
						
						echo Booking::insert($option);
			
					}
			
			
					if($type == "address"){
						
						echo Address::insert();
			
					}
			
			
					if($type == "contact_info"){
						
						echo Contact::insert_info();
			
					}
			
			
					if($type == "opening_hours"){
						
						echo Opening_hours::insert();
			
					}
							
			
					if($type == "socialmedia"){
						
						echo Socialmedia::insert();
			
					}
							
			
			echo "</div>";



			$content = ob_get_contents();


			ob_end_clean();


			return $content;


		}


	}


?>