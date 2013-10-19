<p>
	<label>קישור נוכחי:</label>
	<?php if ($item->getHref()): ?>
		<?php echo $item; ?>
	<?php else: ?>
		ללא
	<?php endif; ?>
</p>
<hr class="small" />
<?php if ($item->canEditTitle()): ?>
	<p>
		<label>כותרת לקישור:</label>
		<input type="text" class="text medium" name="<?php echo $item->getItemIdentifier(); ?>[title]" value="<?php echo $item->getTitle(); ?>" />
	</p>
<?php endif; ?>
<p>
	<label class="strong">החלפת קישור:</label>
</p>
<?php if ($item->canEditHref()): ?>
	<p>
		<label>כתובת URL:</label>
		<input type="text" class="text medium" name="<?php echo $item->getItemIdentifier(); ?>[href]" value="" />
	</p>
<?php endif; ?>
<?php if ($item->canUploadFile()): ?>
	<p>
		<label>העלאת קובץ מהמחשב:</label>
		<input class="text" type="file" name="<?php echo $item->getItemIdentifier(); ?>" />
	</p>
<?php endif; ?>
<?php if ($item->canEditTarget()): ?>
	<p>
		<label>פתח קישור בחלון חדש</label>
		<input type="checkbox" name="<?php echo $item->getItemIdentifier(); ?>[target]" <?php if ($item->shouldOpenInNewWindow()) echo 'checked="checked"'; ?> />
	</p>
<?php endif; ?>