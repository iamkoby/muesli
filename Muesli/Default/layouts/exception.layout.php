<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css" media="all">
		@import url("/muesli/css/exception.css");
    </style>	
	<!--[if lt IE 8]><style type="text/css" media="all">@import url("/muesli/css/ie.css");</style><![endif]-->
</head>
<body>
	<div id="hld">	
		<div class="wrapper">
			<div id="box">
				<?php $this->output('content'); ?>
			</div>
			<div id="footer">
				<p class="left"><?php echo date('d/m/Y H:i'); ?></p>
				<p class="right">powered by <a href="#">Muesli</a> v1.0</p>
			</div>
			
		</div>
	</div>
</body>
</html>