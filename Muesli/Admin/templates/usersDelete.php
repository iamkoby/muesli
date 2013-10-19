<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/Users/deleteDo'); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />
	<div class="block">
		<div class="block_head">
			<h2>מחיקת משתמש "<?php echo $user['name']; ?>"</h2>
			<a href="<?php echo $this->routing->url('/Users/edit?id=' . $user['id']); ?>" class="submit small">ביטול</a>
		</div>	
		<div class="block_content">	
			<div class="message errormsg" style="display: block;">
				<p>האם אתה בטוח שברצונך למחוק את המשתמש?<br/>לא ניתן לשחזר את המידע לאחר המחיקה.<br/>במידה והינך מחובר באמצעות משתמש זה, המערכת תתנתק.</p>
			</div>
			<p>
				<input type="submit" class="submit delete" value="כן, מחק" />
			</p>
		</div>
	</div>
</form>