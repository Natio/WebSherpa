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
<p>You  requested a link to reset your WebSherpa password. 
Please set a new password by following the link below:</p>

<a href="<? echo $this->result['link'];?>"><? echo $this->result['link'];?></a>
</body>
</html>
