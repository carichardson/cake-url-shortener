<h2>Previously Shrunken URL's</h2>

<table class="table table-striped table-bordered table-hover">
    <tr>
        <th><?php echo $this->Paginator->sort('url', 'URL'); ?></th>
        <th><?php echo $this->Paginator->sort('code', 'Code'); ?></th>
        <th><?php echo $this->Paginator->sort('hit_count', 'Clicks'); ?></th>
		<th><?php echo $this->Paginator->sort('created', 'Created'); ?></th>
		<th class="last">Stats</th>
	</tr>
       <?php foreach($short_urls as $short_url): ?>
    <tr>
        <td><?php echo $short_url['ShortUrl']['url']; ?></td>
		<td><a href="//<?php echo Configure::read('Server.base_url') ?>/<?php echo $short_url['ShortUrl']['code']; ?>"><i class="icon-globe"></i>&nbsp;<?php echo $short_url['ShortUrl']['code']; ?></td>
		<td><?php echo $short_url['ShortUrl']['hit_count']; ?></td>
		<td><?php echo $short_url['ShortUrl']['created']; ?></td>
		<td class="last"><a href="/stats/view/<?php echo $short_url['ShortUrl']['code']; ?>" class="btn"><i class="icon-search"></i>&nbsp;View</td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="pagination">
    <ul>
        <li><?php echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')); ?></li>
        <?php echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentTag' => 'span')); ?>
        <li><?php echo $this->Paginator->next( __('next').' >', array(), null, array('class' => 'next disabled')); ?></li>
    </ul>
</div>
