<?php
	function is_get(){

		if($_SERVER["REQUEST_METHOD"] == "GET"){

			return true;
		}

		return false;
	}

	function is_post(){

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			return true;
		}

		return false;
	}

	function is_ajax(){

		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
		  !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
		  strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest"){

			return true;
		}else{

			return false;
		}
	}

	function clear_str($var){

		return trim(strip_tags($var));
	}

	function clear_int($var){

		return (int)$var;
	}

	function check_domain($key, $domains){

		return array_search($key, $domains);
	}
?>