<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? echo $this->result['title']?></title>
<style>
body{
	font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
	
}
p{
	font-weight:200;
	font-size:20px;
}
</style>

</head>

<body>

<h2>Hi <? echo $this->result['username']?>,</h2>
<p>You  requested a link to reset your
   
    <a href="<? echo 'http://'.PCConfigManager::sharedManager()->getValue('DOMAIN_NAME')?>">WebSherpa</a>
   
    password. 
Please set a new password by following the link below:</p>

<p>oYour new password is: <? echo $this->result['pass'];?></p>
<p>Please <? if(defined("DEBUG")): ?>
    <a href="http://localhost:8888">login</a>
    <? else: ?>
    <a href="http://websherpa.me">login</a>
    <? endif;?> and change it now!!</p>
</body>
</html>