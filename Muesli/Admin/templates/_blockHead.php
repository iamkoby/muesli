<h2>
	<a href="<?php echo $this->routing->url('/Pages'); ?>">כל העמודים</a>
	<?php 
		$path = $item->getFullPath(); 
		$lastPathItem = array_pop($path);
	?>	
	<?php foreach($path as $pathItem): ?>
		/ <a href="<?php echo $this->routing->url('/Item/show?address='.$pathItem->getAddress()); ?>"><?php echo $pathItem->getAdminTitle(); ?></a>
	<?php endforeach; ?>
	/ <?php echo $lastPathItem->getAdminTitle(); ?>
	<?php if (isset($extra_text)): ?>
		/ <?php echo $extra_text; ?>
	<?php endif; ?>
	<span><?php echo $item->getAdminDescription(); ?></span>
</h2>