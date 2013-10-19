<?php if ($item->hasPicture()): ?>
	<p class="picture edit">
		<label>תמונה נוכחית:</label>
		<?php echo $item; ?>
	</p>
<?php endif; ?>
<p>
	<label class="strong">החלפת תמונה:</label>
</p>
<p>
	<label>העלה תמונה ממחשבך:</label>
	<input class="text" type="file" name="<?php echo $item->getItemIdentifier(); ?>" />
</p>
<?php /* ?>
<p>
	<label>או בחר בתמונה שכבר העלת:</span></label>
	<input type="text" class="text library" name="<?php echo $item->getItemIdentifier(); ?>[library]" value="" />
</p>
*/ ?>
<p>
	<label>או השתמש בתמונה מהאינטרנט: <span>(תמונה זו תועתק אל שרת זה. השתמש באפשרות זו רק אם הינך בעל זכויות של התמונה.)</span></label>
	<input type="text" class="text medium" name="<?php echo $item->getItemIdentifier(); ?>[src]" value="" />
</p>
<p>
	<label>שימו לב:<span> יש להשתמש רק באחת האפשרויות להחלפת תמונה.</span></label>
</p>
<hr class="small noclear" />
<p>
	<label>תיאור אלטרנטיבי:</label>
	<input type="text" class="text medium" name="<?php echo $item->getItemIdentifier(); ?>[alt]" value="<?php echo $item->getPicAlt(); ?>" /> 
</p>
<p>
	<label>כותרת <span>(מופיעה כאשר עוברים על גבי התמונה)</span>:</label>
	<input type="text" class="text medium" name="<?php echo $item->getItemIdentifier(); ?>[title]" value="<?php echo $item->getPicTitle(); ?>" /> 
</p>