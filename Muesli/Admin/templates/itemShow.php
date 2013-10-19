<?php $this->extend('main.layout'); ?>

<div class="block bold">
	<div class="block_head">
		<?php $this->embed('_blockHead'); ?>
		<?php if ($item->hasBarBlock()): ?>
			<?php $this->embed($item->getBarBlockTemplate()); ?>
		<?php endif; ?>
	</div>
	<?php if ($item->hasHeaderBlock()): ?>
		<?php $this->embed($item->getHeaderBlockTemplate()); ?>
	<?php endif;?>
</div>

<?php foreach($item->getChildren() as $child): ?>
	<?php $this->embed('_item', array('item'=>$child)); ?>
<?php endforeach; ?>