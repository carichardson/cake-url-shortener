<?php
class ShortUrl extends AppModel {
	var $name = 'ShortUrl';
	
	// validatio criteria
	var $validate = array(
	    'url' => array(
	        'rule' => array('url', true),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Please enter a valid url to shorten.'
	    ),
		'code' => array(
			'between' => array(
				'rule'		=> array('between', 1, 10),
				'message'	=> 'Extension must be ten characters of less.'
			),
			'characters' =>	array(
				'rule'		=> 'alphaNumericDashUnderscore',
				'message'	=> 'Extension can only contain letters, numbers, dash and underscore.'
			),
			'reserved' => array(
				'rule'    => 'reservedCodes',
				'message'	=> 'This code is unavailable because it is reserved.'
			)
		)
	);
	
	// validation function to ensure code is not in reserved list
	function reservedCodes($data) {
		if(in_array($data['code'], Configure::read('InvalidCodes'))) {
			return false;
		}
		
		return true;
	}

	// validation function to check that code only alphanumeric and _-
	function alphaNumericDashUnderscore($data) {
		return preg_match('|^[0-9a-zA-Z_-]*$|', $data['code']);
	}
	
	// update the hit count of a short url
	function updateHitCount($short_url_id){
		$this->query('UPDATE short_urls SET hit_count = hit_count + 1, modified = NOW() WHERE id='.$short_url_id);
	}
	
	// generate a short code, make sure it does not already exist
	function generateCode($code='') {
		
		if(empty($code)) {
			$code = $this->randomString();
		}
		
		while(true) {	
			
			$shortUrl = $this->findByCode($code);
			if(empty($shortUrl)) break;
			$code = $this->randomString();
		}
		
		return $code;
	}
}