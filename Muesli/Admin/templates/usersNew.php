<?php $this->extend('main.layout'); ?>

<form action="<?php echo $this->routing->url('/Users/save'); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="user_id" value="" />
	<div class="block">
		<div class="block_head">
			<h2>משתמש חדש</h2>
			<a href="<?php echo $this->routing->url('/Users'); ?>" class="submit small">ביטול</a>
		</div>	
		<div class="block_content">	
			<p>
				<label>שם:<label>
				<input type="text" class="text small" name="name" value="" /> 
			</p>
			<p>
				<label>שם משתמש (לצורך התחברות):<label>
				<input type="text" class="text small ltr" name="username" value="" /> 
			</p>
			<p>
				<label>סיסמא:<label>
				<input type="password" class="text small ltr" name="password" value="" /> 
			</p>
			<p>
				<input type="submit" class="submit small" value="שמור" />
			</p>
		</div>
	</div>
</form>