<h2>Enter a url and desired short code below</h2>

<?php if(isset($shortened) AND !empty($shortened)): ?>
		
		<p>Your shortened url:</p>
		<a href="http://<?php echo $shortened;?>"><?php echo $shortened; ?></a>
				
		<br /><br />
		<a href="/shorten">Shorten Another URL</a>
		<br />
		
		<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $shortened; ?>" data-text="<?php echo $title; ?>" data-size="large">Tweet</a>
		
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		
<?php else:
		
		echo $this->Form->create(array('data-validate' => 'parsley'));
		echo $this->Form->input('url', array('label' => false, 'class' => 'span12', 'label' => 'URL to shorten:', 'data-type' => 'urlstrict'));
		echo $this->Form->input('code', array('label' => false, 'class' => 'span12', 'label' => 'Desired extension', 'required' => false, 'pattern' => '[A-Za-z0-9_-]*'));
		echo '<small>*Leave empty to generate one</small><br /><br />';
		echo $this->Form->submit('Shrink It!',array('class' => 'btn-large btn-primary'));
		
		echo $this->Form->end();
		
endif; ?>

<hr />

<h2>You can also use this handy bookmarklet*<br />
<small>* May not work when JavaScript errors are present on page</small></h2>

<p>
<a title="ChrisRichardson.ca" id="chris-bookmarklet" class="btn" href="<?php

	$url = 'javascript:(function() { var s = document.createElement("script"); s.setAttribute("id", "edmarklet_js"); s.setAttribute("type", "text/javascript"); s.setAttribute("src", "//' . Configure::read('Server.base_url') . '/js/bookmarklet.js"); (top.document.body || top.document.getElementsByTagName("head")[0]).appendChild(s); })();';

	$url = str_replace(' ', '%20', $url);
	$url = str_replace('{', '%7B', $url);
	$url = str_replace('}', '%7D', $url);
	$url = str_replace('"', '%22', $url);
	$url = str_replace('|', '%7C', $url);
	
	echo $url;

?>"><i class="icon-bookmark"></i>&nbsp;ChrisRichardson.ca</a>

<br />

<p>Drag above button to your bookmarks bar</p>