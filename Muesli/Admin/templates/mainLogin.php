<?php $this->extend('main.layout'); ?>
<form action="<?php echo $this->routing->url('/Main/loginDo'); ?>" method="post">
	
	<div class="block small center login">
		<div class="block_head">
			<h2>כניסה</h2>
			<ul>
				<li><a href="/">חזרה לאתר</a></li>
			</ul>
		</div>
		<div class="block_content">
			<?php if ($this->user->getFlash('error')): ?>
				<div class="message errormsg"><p>המידע שהוזן אינו נכון ולא יכול לשמש לצורך התחברות למערכת. בדקו שנית את המידע שהזנתם. יש להקפיד על הזנת אותיות רישיות.</p></div>
			<?php endif; ?>
			<p>
				<label>שם משתמש:</label>
				<input type="text" class="text ltr" name="username" value="" />
			</p>
			<p>
				<label>סיסמא:</label>
				<input type="password" class="text ltr" name="password" value="" />
			</p>
			<p>
				<input type="submit" class="submit" value="התחברות" /> &nbsp; 
				<?php /* ?><input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme">Remember me</label> */ ?>
			</p>
		</div>			
	</div>
</form>