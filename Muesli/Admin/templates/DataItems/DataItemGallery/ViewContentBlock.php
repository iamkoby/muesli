<ul class="imglist">
	<?php $i=0;?>
	<?php foreach ($item->getPictures() as $picture): ?>
		<?php if ($i++ > 14) break; ?>
		<li><?php echo $picture->get('picture'); ?></li>
	<?php endforeach; ?>
</ul>