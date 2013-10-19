<?php $first=true; ?>
<?php foreach($item->getChildren() as $child): ?>
	<?php if ($child->canEdit()): ?>
		<?php if ($first) $first=false; else echo '<hr />'; ?>
		<?php if ($child->getAdminTitle()): ?>
			<label class="strong"><?php echo $child->getAdminTitle(); ?>:</label>
		<?php endif; ?>
		<?php echo $this->render($child->getEditContentBlockTemplate(),array('item'=>$child)); ?>
	<?php endif; ?>
<?php endforeach; ?>