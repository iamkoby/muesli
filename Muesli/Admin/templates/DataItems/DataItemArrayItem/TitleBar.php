<h2>
	פריט מס' 
	<?php echo $item->getFullTitle(); ?>
	<?php if ($senior = $item->getAdminSenior()): ?>
		<span><?php echo strip_tags($item->get($senior)); ?></span>
	<?php endif; ?>
</h2>
<a class="btn edit" href="<?php echo $this->routing->url('/Item/edit?address=' . $item->getAddress()); ?>" title="עריכה"></a>