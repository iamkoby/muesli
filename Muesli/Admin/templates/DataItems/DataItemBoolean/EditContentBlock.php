<p>
	<select class="text small" name="<?php echo $item->getItemIdentifier(); ?>[value]" >
		<option value="0" <?php if (!$item->isTrue()) echo 'selected="selected"'; ?>>לא</option>
		<option value="1" <?php if ($item->isTrue()) echo 'selected="selected"'; ?>>כן</option>
	</select>
</p>