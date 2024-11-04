<?php
session_start();
//ゲームの状態をリセット
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>スロットゲーム</title>
</head>

<body>
	<h1>大学生のスロット生活</h1>
	<hr color="red">
	<form action="start.php" method="get" />
  	<input type="submit" value="スタート" />
	</form>
	<hr color="red">
	<form action="rule.php" method="get" />
  	<input type="submit" value="あそびかた">
	<hr color="red">
	</form>
</body>
</html>
