<?php

$start = memory_get_usage();
$startTime = microtime(true);
require '../CSRF.php';
$ss = CSRF::init();

if (empty($_POST)) {
    $ss->validate();
}
$endTime = (round(microtime(true) - $startTime, 5));
echo '<pre>';
echo 'Memory Usaged: ', (memory_get_usage() - $start) / 1024, 'KB <br/>';
echo 'Timeline: ', $endTime, 'seconds';
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<form action="" method="POST" role="form">
    <legend>Form Title</legend>
    <?= $ss->getInput() ?>
    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="" id="" placeholder="Input...">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<!-- jQuery -->
<script src="//code.jquery.com/jquery.js"></script>
<!-- Bootstrap JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</body>
</html>