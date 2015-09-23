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

<h2>NAME: <? echo $this->result['name'];?></h2>
<h2>EMAIL: <? echo $this->result['email'];?></h2>
<h2>OBJECT: <? echo $this->result['object'];?></h2>

<?
    $user = $this->result['user'];
    if(isset($user)):
?>

<h3>IDENTIFIER: <? echo $user->getIdentifier();?></h3>


<? endif;?>
<p><? echo $this->result['text'];?></p>

</body>
</html>