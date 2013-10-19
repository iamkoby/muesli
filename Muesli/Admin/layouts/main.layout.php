<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->project->getProjectName(); ?> :: ממשק ניהול</title>
    <style type="text/css" media="all">
		@import url("/muesli/css/heb.css");
		@import url("/muesli/css/jquery.wysiwyg.css");
    </style>
	<?php echo $this->stylesheets; ?>
	<!--[if lt IE 8]><style type="text/css" media="all">@import url("/muesli/css/ie.css");</style><![endif]-->
	<script type="text/javascript" src="/muesli/js/jquery.js"></script>
	<script type="text/javascript" src="/muesli/js/jquery.filestyle.mini.js"></script>
	<script type="text/javascript" src="/muesli/js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="/muesli/js/jquery.pngfix.js"></script>
	<script type="text/javascript" src="/muesli/js/main.js"></script>
	<?php echo $this->javascripts; ?>
</head>
<body>
	<div id="hld">
		<div class="wrapper">
			<?php if ($this->user->isAuthenticated()): ?>
				<div id="header">
					<h1><a href="<?php echo $this->routing->url('/'); ?>"><?php echo $this->project->getProjectName(); ?></a></h1>
					<ul id="nav">
						<li><a href="<?php echo $this->routing->url('/Pages'); ?>">עמודים</a></li>
						<li><a href="<?php echo $this->routing->url('/Users'); ?>">משתמשים</a></li>
					</ul>
					<p class="user">שלום <?php echo $this->user->getUserName(); ?> | <a href="<?php echo $this->routing->url('/Main/logout'); ?>">התנתקות</a></p>
				</div>
			<?php endif; ?>
			
			<?php $this->output('content'); ?>
			
			<div id="footer">
				<p class="right"><a href="/">חזרה לאתר</a></p>
				<p class="left">מופעל על <a href="#">מוזלי</a> <?php echo Muesli::version(); ?></p>
			</div>
		</div>		
	</div>
</body>
</html>