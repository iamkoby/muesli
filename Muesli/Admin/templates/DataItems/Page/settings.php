<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/DataPage/settingsSave?address='.$page->getAddress()); ?>" method="post" enctype="multipart/form-data">
	<div class="block bold">
		<div class="block_head">
			<?php echo $this->render('_blockHead',array('item'=>$page)); ?>
			<a href="<?php echo $this->routing->url('/Item/show?address='.$page->getAddress()); ?>" class="submit small">ביטול</a>
			<input type="submit" class="submit small" value="שמור" />
		</div>
		<div class="block_content">
			<?php $children = array($page->get('_pageTitle'), $page->get('_pageMetaDescription'), $page->get('_pageMetaKeywords')); ?>
			<?php foreach($children as $child): ?>
				<p>
					<label><?php echo $child->getAdminTitle(); ?>:</label>
					<?php echo $this->render($child->getEditContentBlockTemplate(),array('item'=>$child)); ?>
				</p>
			<?php endforeach; ?>
		</div>
	</div>

</form>