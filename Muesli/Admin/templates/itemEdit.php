<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/Item/save?address='.$item->getAddress()); ?>" method="post" enctype="multipart/form-data">
	<div class="block bold">
		<div class="block_head">
			<?php $this->embed('_blockHead'); ?>
			<?php $this->embed($item->getEditBarBlockTemplate()); ?>
		</div>
		<div class="block_content">
			<?php $this->embed($item->getEditContentBlockTemplate()); ?>
		</div>
	</div>
</form>

<?php foreach($item->getUneditableChildren() as $child): ?>
	<?php $this->embed('_item', array('item'=>$child)); ?>
<?php endforeach; ?>