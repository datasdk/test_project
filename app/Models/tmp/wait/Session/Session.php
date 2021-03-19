<?php

namespace App\Models\Api\Api;
	

	class Session {
		

		public static function get($name){



			if(isset($_SESSION[$name])){

				return $_SESSION[$name];

			} else {

				return false;

			}


		}


		public static function set($name,$value,$time=0){


			$_SESSION[$name] = $value;

		}
		

		public static function remove($name){
			

			if(isset($_SESSION[$name])){

				unset($_SESSION[$name]);

			}
			

		}

		public static function id(){

			return session_id();

		}
		

	}
	
	
?>