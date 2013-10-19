<a href="<?php echo $this->routing->url('/DataArray/new?address=' . $item->getAddress()); ?>" class="submit mid">הוסף פריט</a>
<?php if ($item->canEditSorting()): ?>
	<a href="<?php echo $this->routing->url('/DataArray/sorting?address=' . $item->getAddress()); ?>" class="submit mid">קבע סדר</a>
<?php endif; ?>
<a href="<?php echo $this->routing->url('/DataArray/deleteAll?address=' . $item->getAddress()); ?>" class="submit delete">מחק הכל</a>