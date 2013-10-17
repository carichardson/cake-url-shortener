<?php
class StatsController extends AppController {
	var $name = 'Stats';
	var $uses = array('ShortUrl');
	var $paginate = array(
		'ShortUrl' => array(
			'limit' => 10,
			'order' => array('created' => 'desc')
		)
	);
	
	// paginate and show all short_urls
	function index() {
		$this->set('short_urls', $this->paginate());
	}
	
	// gather some stats on a short_url
	function view($code='') {
		App::uses('Sanitize', 'Utility');
		
		$shortUrl = $this->ShortUrl->findByCode(Sanitize::paranoid($code, array('-', '_')));
		
		if(!empty($shortUrl)) {
			
			$clickStats = array();
			
			if($shortUrl['ShortUrl']['hit_count'] > 0) {
				
				// percent users on mobile
				$mobile = $this->ShortUrl->query('SELECT COUNT(id) AS mobile FROM clicks WHERE mobile=1 AND short_url_id='.$shortUrl['ShortUrl']['id']);
				$clickStats['percent_mobile'] = round(100*($mobile[0][0]['mobile'] / $shortUrl['ShortUrl']['hit_count']), 1);
				
				// top five languages
				$languages = $this->ShortUrl->query('SELECT language, COUNT(id) AS users FROM clicks WHERE short_url_id = '.$shortUrl['ShortUrl']['id'].' GROUP BY language ORDER BY users DESC LIMIT 5');
				
				for($i=0; $i<count($languages); $i++){
					$clickStats['languages'][$i]['language'] = $languages[$i]['clicks']['language'];
					$clickStats['languages'][$i]['percent'] = round(100*($languages[$i][0]['users'] / $shortUrl['ShortUrl']['hit_count']), 1);
				}
				
				// top five user_agents
				$user_agents = $this->ShortUrl->query('SELECT user_agent, COUNT(id) AS users FROM clicks WHERE short_url_id = '.$shortUrl['ShortUrl']['id'].' GROUP BY user_agent ORDER BY users DESC LIMIT 5');
				
				for($i=0; $i<count($user_agents); $i++){
					$clickStats['user_agents'][$i]['user_agent'] = $user_agents[$i]['clicks']['user_agent'];
					$clickStats['user_agents'][$i]['percent'] = round(100*($user_agents[$i][0]['users'] / $shortUrl['ShortUrl']['hit_count']), 1);
				}
				
				// top five referers
				$referers = $this->ShortUrl->query('SELECT referer, COUNT(id) AS users FROM clicks WHERE short_url_id = '.$shortUrl['ShortUrl']['id'].' GROUP BY referer ORDER BY users DESC LIMIT 5');
				
				for($i=0; $i<count($referers); $i++){
					$clickStats['referers'][$i]['referer'] = $referers[$i]['clicks']['referer'];
					$clickStats['referers'][$i]['percent'] = round(100*($referers[$i][0]['users'] / $shortUrl['ShortUrl']['hit_count']), 1);
				}
			}
			
			$this->set(compact('shortUrl', 'clickStats'));
			
		} else {
			$this->Session->setFlash("Short URL not found!");
			$this->redirect('/stats');
		}
	}
}