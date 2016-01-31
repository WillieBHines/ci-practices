<!DOCTYPE html>
<html>
<head>
	<title>WH Workshops: Admin</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes/workshop/bootstrap.readable.min.css">

<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

</head>
<body>
	<div class="container">
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>
	</div> <!-- end of container -->
</body>
</html>
