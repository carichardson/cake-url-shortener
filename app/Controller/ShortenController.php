<?php
class ShortenController extends AppController {
	var $name = 'Shorten';
	var $uses = array('ShortUrl');

	// page that lets users create short urls
	function index() {
		
		// check if correct post data is present
		if(!empty($this->request->data['ShortUrl']['url'])) {
			
			// nicer, short variable to work with, for readability
			$url = $this->request->data['ShortUrl']['url'];
			
			// make sure url prefaced with http://
			if( !(strpos($url, 'https://') === 0 OR strpos($url, 'http://') === 0) ) {
				$url = 'http://' . $url;
			}
			
			// sanitize and try given code or create new one
			App::uses('Sanitize', 'Utility');
			$code = $this->ShortUrl->generateCode(Sanitize::paranoid($this->request->data['ShortUrl']['code'], array('-', '_')));
			
			// create the short url
			$this->ShortUrl->create(array('code' => $code, 'url' => $url));
			
			// test that data is valid
			if($this->ShortUrl->validates()) {
				
				$this->ShortUrl->save();
				
				// set short url to present to user
				$this->set('shortened', Configure::read('Server.base_url').'/'.$code);
				
				// Retrieve link title for social sharing
				$this->set('title', $this->ShortUrl->getTitleForUrl($url));
			}
		}
	}
	
	function bookmarklet() {
		$response = array('success' => false, 'error' => 'No url provided.');
		
		if(!empty($_REQUEST['url'])) {
			
			// get new code
			$code = $this->ShortUrl->generateCode();
			
			// create and validate new url
			$this->ShortUrl->create(array('code' => $code, 'url' => $_REQUEST['url']));
			if($this->ShortUrl->validates()) {
				
				$this->ShortUrl->save();
				$response = array('success' => true, 'url' => Configure::read('Server.base_url').'/'.$code);
			
			// url improperly formatted
			} else {
				$response['error'] = 'Please provide a valid url.';
			}
		}
		
		echo $_GET['callback'].'('.json_encode($response).')';
		exit;
	}
}