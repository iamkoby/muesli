<?php $this->extend('main.layout'); ?>

<div class="block">
	<div class="block_head">
		<h2>משתמשים</h2>
		<a href="<?php echo $this->routing->url('/Users/new'); ?>" class="submit mid">משתמש חדש</a>
	</div>	
	<div class="block_content">			
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<th>שם משתמש</th>
					<th>שם</th>
					<th>תאריך כניסה אחרון</th>
				</tr>
				
				<?php foreach ($users as $user): ?>
					<tr>
						<td><a href="<?php echo $this->routing->url('/Users/edit?id=' . $user['id']); ?>"><?php echo $user['username']; ?></a></td>
						<td><?php echo $user['name']; ?></td>
						<td><?php if ($user['last_entrance']) echo date('d/m/Y H:i', $user['last_entrance']); else echo 'טרם נכנס למערכת'; ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
	</div>
</div>