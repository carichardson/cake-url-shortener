<?php

App::uses('Model', 'Model');

class AppModel extends Model {
	
	function randomString($length = 6) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz-_';
	    $string = '';    
	    for ($p = 0; $p < $length; $p++) {
	        $string .= $characters[mt_rand(0, strlen($characters)-1)];
	    }
	    return $string;
	}

	function getTitleForUrl($url) {
		$result = '';
		
		$data = file_get_contents($url);
		if(strlen($data) > 0) {
			preg_match("/\<title\>(.*)\<\/title\>/", $data, $title);
			if(isset($title[1]) AND !empty($title[1])) {
				$result = $title[1];
			}
		}
		return $result;
	}
}