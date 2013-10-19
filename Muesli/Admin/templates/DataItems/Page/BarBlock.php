<?php if ($item->canEditMeta()): ?>
	<a href="<?php echo $this->routing->url('/DataPage/settings?address=' . $item->getAddress()); ?>" class="submit mid">הגדרות עמוד</a>
<?php endif; ?>