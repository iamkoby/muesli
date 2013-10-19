<h2>
	<?php echo $item->getAdminTitle(); ?>
	<span><?php echo $item->getAdminDescription(); ?></span>
</h2>
<?php $edit_url = ($item->canEdit()) ? '/Item/edit': '/Item/show'; ?>
<a class="btn edit" href="<?php echo $this->routing->url($edit_url.'?address='.$item->getAddress()); ?>" title="עריכה"></a>