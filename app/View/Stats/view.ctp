

<h2>URL Details</h2>
	
<table class="table table-striped table-bordered table-hover">
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr><tr>
		<td>Long URL:</td><td><a href="<?php echo $shortUrl['ShortUrl']['url']; ?>"><i class="icon-globe"></i>&nbsp;<?php echo $shortUrl['ShortUrl']['url']; ?></a></td>
	</tr><tr>
		<td>Short URL:</td><td><a href="//<?php echo Configure::read('Server.base_url') ?>/<?php echo $shortUrl['ShortUrl']['code']; ?>"><i class="icon-globe"></i>&nbsp;http://<?php echo Configure::read('Server.base_url') ?>/<?php echo $shortUrl['ShortUrl']['code']; ?></td>
	</tr><tr>
		<td>Clicks:</td><td><?php echo $shortUrl['ShortUrl']['hit_count']; ?></td>
	</tr><tr>
		<td>Created:</td><td><?php echo $shortUrl['ShortUrl']['created']; ?></td>
	</tr><tr>
		<td>Modified:</td><td><?php echo $shortUrl['ShortUrl']['modified']; ?></td>
	</tr><tr>
		<td>ID:</td><td><?php echo $shortUrl['ShortUrl']['id']; ?></td>
	</tr>
</table>

<?php if(!empty($clickStats)): ?>
	
	<h2>Click Stats</h2>
	
	<ul>
		<li>Percent Mobile: <?php echo $clickStats['percent_mobile']; ?>%</li>
		
		<?php if(isset($clickStats['languages']) AND !empty($clickStats['languages'])): ?>
			<li>Languages:
				<ol>
					<?php foreach($clickStats['languages'] as $language):
						echo '<li>'.$language['language'].' - '.$language['percent'].'%</li>';
					endforeach; ?>
				</ol>
			</li>
		<?php endif;?>
		
		<?php if(isset($clickStats['user_agents']) AND !empty($clickStats['user_agents'])): ?>
			<li>User Agents:
				<ol>
					<?php foreach($clickStats['user_agents'] as $ua):
						echo '<li>'.$ua['user_agent'].' - '.$ua['percent'].'%</li>';
					endforeach; ?>
				</ol>
			</li>
		<?php endif;?>
		
		<?php if(isset($clickStats['referers']) AND !empty($clickStats['referers'])): ?>
			<li>Referers:
				<ol>
					<?php foreach($clickStats['referers'] as $referer):
						echo '<li>'.$referer['referer'].' - '.$referer['percent'].'%</li>';
					endforeach; ?>
				</ol>
			</li>
		<?php endif;?>
	</ul>

<?php endif; ?>