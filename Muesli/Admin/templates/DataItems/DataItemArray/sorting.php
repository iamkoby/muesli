<?php $this->extend('main.layout'); ?>
<?php $this->javascripts->add('/muesli/js/sorting.js'); ?>

<form action="<?php echo $this->routing->url('/DataArray/saveSorting?address=' . $item->getAddress()); ?>" method="post" enctype="multipart/form-data">
	<div class="block bold">
		<div class="block_head">
			<?php $this->embed('_blockHead', array('extra_text'=>'סידור')); ?>
			<a href="<?php echo $this->routing->url('/Item/show?address='.$item->getAddress()); ?>" class="submit small">חזרה</a>
			<?php /*?><input type="submit" class="submit small" value="שמור" /> */ ?>
		</div>
		<div class="block_content">
			<label class="strong">סדר המערך:</label>
			<ul class="bulletless">
				<?php /*?>
				<li><input name="" disabled="disabled" class="radio" type="radio">מהחדש ביותר לישן ביותר</li>
				<li><input name="" disabled="disabled" class="radio" type="radio">מהישן ביותר לחדש ביותר</li>
				<li><input name="" disabled="disabled" class="radio" type="radio">מהמעודכן ביותר למיושן ביותר</li>
				<li><input name="" disabled="disabled" class="radio" type="radio">מהמיושן ביותר למעודכן ביותר</li>
				*/  ?>
				<li><input name="type" checked="checked" class="radio" type="radio">ידנית</li>
			</ul>
		</div>
	</div>

	<div class="block bold">
		<div class="block_head">
			<h2>סידור ידני</h2>
		</div>
		<div class="block_content">
			<table id="table_order" cellpadding="0" cellspacing="0" width="100%">
				<tr class="title">
					<th>מס'</th>
					<th>פריט</th>
					<th>אפשרויות</th>
				</tr>
				<?php foreach ($item->getChildren() as $child): ?>
					<tr item="<?php echo $child->getId(); ?>">
						<td><?php echo $child->getId(); ?></td>
						<td><?php $senior = $child->getAdminSenior(); if ($senior) echo $child->get($senior); ?></td>
						<td><a class="up" href="#">העלה</a> | <a class="down" href="#">הורד</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

</form>