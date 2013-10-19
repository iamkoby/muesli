<?php $this->extend('main.layout'); ?>

<div class="block">
	<div class="block_head">
		<h2>עמודים</h2>
	</div>	
	<div class="block_content">
		<p>בחרו בעמוד על מנת לערוך את תכניו, על ידי לחיצה על שמו.</p>
		<hr />
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<th>שם</th>
				<th>תיאור</th>
			</tr>
			
			<?php foreach ($pages as $page): ?>
				<tr>
					<td><a href="<?php echo $this->routing->url('/Item/show?address='.$page->getName()); ?>"><?php echo $page->getAdminTitle(); ?></a></td>
					<td><?php echo $page->getAdminDescription(); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>