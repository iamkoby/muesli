<p>
	<textarea class="wysiwyg <?php if ($direction = $item->getTextDirection()) echo $direction; else echo 'rtl'; ?>" name="<?php echo $item->getItemIdentifier(); ?>[value]"><?php echo $item; ?></textarea>
</p>