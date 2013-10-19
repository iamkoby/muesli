<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/DataArray/saveNew?address='.$item->getAddress()); ?>" method="post" enctype="multipart/form-data">
	<div class="block bold">
		<div class="block_head">
			<?php $this->embed('_blockHead', array('extra_text'=>'חדש')); ?>
			<a href="<?php echo $this->routing->url('/Item/show?address='.$item->getAddress()); ?>" class="submit small">ביטול</a>
			<input type="submit" class="submit small" value="שמור" />
		</div>
		<div class="block_content">
			<?php $this->embed('DataItems/DataItemParent/EditContentBlock', array('item'=>$item->getEmptyChild())); ?>
		</div>
	</div>

</form>