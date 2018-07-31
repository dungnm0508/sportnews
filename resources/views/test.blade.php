<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dũng Đẹp Zai</title>
</head>
<body>
	
	@foreach($news as $val)
	
	<a href="{{'http://dantri.com.vn'.$val->link}}" target="_blank"><?php echo $val->title; ?></a><br>

	@endforeach

</body>
</html>