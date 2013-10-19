<div class="block">
	<div class="block_head">			
		<?php $this->embed($item->getTitleBarTemplate(), array('item'=>$item)); ?>
	</div>	
	<div class="block_content">	
		<?php $this->embed($item->getViewContentBlockTemplate(), array('item'=>$item)); ?>
	</div>
</div>