<?php if ($item->canEditMeta()): ?>
	<div class="block_content">
		<p>
			<label class="inline">כותרת העמוד:</label>
			<?php echo $item->getTitle(); ?>
		</p>
		<p>מידע עבור מנועי חיפוש:</p>
		<p>
			<label class="inline">תיאור העמוד:</label>
			<?php echo $item->getMetaDescription(); ?>
		</p>
		<p>
			<label class="inline">מילות מפתח:</label>
			<?php echo $item->getMetaKeywords(); ?>
		</p>
	</div>
<?php endif; ?>