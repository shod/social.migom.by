<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="ru" />
	
		<link rel="stylesheet" href="/css/reset.css" type="text/css" />
		<link rel="stylesheet" href="/css/oldDefault.css" type="text/css" />
		<link rel="stylesheet" href="/css/login.css" type="text/css" />
	</head>

	<body>		
		<div class="registration">
			<!--HEAD-->
			<div class="head">
				<a title="" href="."><img class="logo" width="214" height="53" alt="Migom.by" src="/images/reg_logo.gif"></a>
				<div class="nav">
					<a href="/about" target="_blank">О проекте</a>
				</div><!--/nav-->
			</div><!--/head-->
			
			<?= $content ?>	
			
			<!--FOOTER-->
			<div class="footer">
				<ul class="copy">
					<li>© 2006 — <?=date('Y') ?> Migom.by Республика Беларусь, г. Минск</li>
				</ul>
			</div><!--/footer-->
		</div><!--/registration-->

	</body>
</html>