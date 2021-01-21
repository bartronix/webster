<!DOCTYPE HTML>
<html>
<head>
<title><?php echo (!empty($this->pageTitle)) ? $this->pageTitle : MAIN_TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="copyright" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="">
<meta name="generator" content="webster framework" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php
foreach ($this->metaTags as $tag) {
    echo $tag."\r\n";
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/default.css">
<?php foreach ($this->cssFiles as $cssFile) {
    echo '<link rel="stylesheet" type="text/css" href="'.BASE_URL.$cssFile.'">'."\r\n";
} ?>
<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
<?php echo $this->content; ?>
<?php foreach ($this->jsFiles as $jsFile) {
    echo '<script type="text/javascript" src="'.BASE_URL.$jsFile.'"></script>'."\r\n";
} ?>
</body>
</html>
