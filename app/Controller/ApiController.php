<?php
class ApiController extends AppController {
	var $name = 'Api';
	var $uses = array('ShortUrl');

	function url() {
		$response = array('success' => false, 'error' => 'No data provided.');
	
		// check for post data
		if(!empty($this->request->data)) {
			
			// check that action is set
			if(!empty($this->request->data['action'])) {
				
				switch ($this->request->data['action']) {
					
					case 'shorten_url':
						$response = $this->_shorten_url();
					break;
					
					case 'retrieve_url':
						$response = $this->_retrieve_url();
					break;
					
					default;
						$response['error'] = 'No api action provided.';
				}
			}
		}
		$this->log($response);
		echo json_encode($response);
		exit;
	}
	
	function _shorten_url() {
		$response = array('success' => false);
		
		if(!empty($this->request->data['url'])) {
			
			// extract url and make sure it is formatted with http
			$url = $this->request->data['url'];
			if( !(strpos($url, 'https://') === 0 OR strpos($url, 'http://') === 0) ) {
				$url = 'http://' . $url;
			}

			// get new code
			$code = $this->ShortUrl->generateCode();

			// create new code and make sure url validates
			$this->ShortUrl->create(array('code' => $code, 'url' => $url));
			if($this->ShortUrl->validates()) {

				// save new short url and 
				$this->ShortUrl->save();
				$response = array('success' => true, 'url' => Configure::read('Server.base_url').'/'.$code);

			// url improperly formatted
			} else {
				$response['error'] = 'Please provide a valid url.';
			}
		} else {
			$response['error'] = 'No url provided to shorten.';
		}
		
		return $response;
	}
	
	function _retrieve_url() {
		$response = array('success' => false);
		
		if(!empty($this->request->data['code'])) {

			// create new code and make sure url validates
			$short_url = $this->ShortUrl->findByCode($this->request->data['code']);
			
			if(!empty($short_url)) {

				$response = array('success' => true, 'url' => $short_url['ShortUrl']['url']);

			// url improperly formatted
			} else {
				$response['error'] = 'Code not found.';
			}
		} else {
			$response['error'] = 'No short code provided.';
		}
		
		return $response;
	}
}