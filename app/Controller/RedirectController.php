<?php
class RedirectController extends AppController {
	var $name = 'Redirect';
	var $uses = array('ShortUrl');

	// function that receives short code and redirects to corresponding url
	function index($code = '') {
		
		if(!empty($code)) {
			
			// sanitize and retrieve url based on code
			App::uses('Sanitize', 'Utility');
			$shortUrl = $this->ShortUrl->findByCode(Sanitize::paranoid($code, array('-', '_')));
			
			if(!empty($shortUrl)) {
				
				$languages = $this->request->acceptLanguage();
				
				// assemble some statistics about short url click
				$clickData = array(
					'short_url_id'	=> $shortUrl['ShortUrl']['id'],
					'mobile'		=> ($this->request->is('mobile') ? 1 : 0),
					'remote_ip'		=> $this->request->clientIp(),
					'language'		=> $languages[0],
					'user_agent'	=> urlencode(str_replace("/","\\", $this->request->header('User-Agent'))),
					'referer'		=> urlencode(str_replace("/","\\", $this->request->referer()))
				);
				
				// record click and stats asynchronously to respond to user faster
				App::uses('AsyncComponent', 'Controller/Component');
				AsyncComponent::run('/redirect/record_stats', $clickData);
				
				// redirect to full length url
				$this->redirect($shortUrl['ShortUrl']['url'], 301, true);
			}
		}
		
		// if no url found, send user to home
		$this->redirect('http://'.Configure::read('Server.base_url'), 301, true);
	}
	
	function record_stats($id, $mobile, $ip, $language, $ua, $referer) {
		
		// make sure request is coming in from the command line
		if(!defined('CRON_DISPATCHER')) {
			$this->redirect('/');
			exit;
		}
		
		$clickData = array(
			'short_url_id'	=> $id,
			'mobile'		=> $mobile,
			'remote_ip'		=> $ip,
			'language'		=> $language,
			'user_agent'	=> str_replace("\\", "/", urldecode($ua)),
			'referer'		=> str_replace("\\", "/", urldecode($referer))
		);
		
		// update total click count
		$this->ShortUrl->updateHitCount($id);
		
		// create and save a click record
		$this->loadModel('Click');
		$this->Click->create();
		$this->Click->save($clickData);
		exit;
	}
}