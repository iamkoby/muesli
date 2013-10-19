<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/DataArray/deleteAllDo?address='.$item->getAddress()); ?>" method="post" enctype="multipart/form-data">
	<div class="block bold">
		<div class="block_head">
			<?php echo $this->render('_blockHead',array('item'=>$item)); ?>
			<a href="<?php echo $this->routing->url('/Item/show?address='.$item->getAddress()); ?>" class="submit small">ביטול</a>
		</div>
		<div class="block_content">
			<div class="message errormsg" style="display: block;">
				<p>האם אתה בטוח שברצונך למחוק את כל הפריטים במערך זה?<br/>לא ניתן לשחזר את המידע לאחר המחיקה.</p>
			</div>
			<p>
				<input type="submit" class="submit delete" value="כן, מחק" />
			</p>
		</div>
	</div>

</form>