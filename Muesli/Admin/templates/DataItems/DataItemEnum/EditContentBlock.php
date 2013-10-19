<?php
$item_value = $item->getValue();
?>
<p>
	<select class="text small" name="<?php echo $item->getItemIdentifier(); ?>[value]" >
		<?php foreach ($item->getOptions() as $key => $value): ?>
			<option <?php if ($item_value == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
		<?php endforeach; ?>
	</select>
</p>