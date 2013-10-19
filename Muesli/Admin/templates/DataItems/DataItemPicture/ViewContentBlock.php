<p class="picture">
	<?php echo $item; ?>
</p>
<p>
	<label>תיאור אלטרנטיבי:</label>
	<?php if ($item->getPicAlt()) echo $item->getPicAlt(); else echo 'ללא'; ?>
</p>
<p>
	<label>כותרת <span>(מופיעה כאשר עוברים על גבי התמונה)</span>:</label>
	<?php if ($item->getPicTitle()) echo $item->getPicTitle(); else echo 'ללא'; ?>
</p>