<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=$this->headMeta()?>
<?=$this->headTitle()?>
<?=$this->headLink()?>
<?=$this->headScript()?>
<link rel="stylesheet" type="text/css" href="/css/jquery.treeview.css" />
<link rel="stylesheet" type="text/css" href="/css/frameset.css"/>
<script type="text/javascript" src="/js/jquery142.js"></script>
<script type="text/javascript" src="/js/jquery.treeview.js"></script>
<script type="text/javascript" src="/js/splitter.js"></script>
</head>
<body>
<?=$this->layout()->content?>
</body>
<?=JsUtils::ob_flush()?>
</html>