<?php $this->extend('exception.layout'); ?>
<h1><?php echo $e->getMessage(); ?></h1>

<h2>Trace:</h2>
<ol>
	<?php foreach (array_reverse($e->getTrace()) as $trace): ?>
		<li>at <strong><?php echo $trace['function']; ?></strong>
		<?php if (isset($trace['file'])): ?>
			in <?php echo $trace['file']; ?>, line <?php echo $trace['line']; ?>
		<?php endif; ?>
		</li>
	<?php endforeach;?>
</ol>