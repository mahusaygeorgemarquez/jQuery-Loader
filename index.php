<html>
	<head>
		<title>PHPWord With Loader</title>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<?php include_once "ajaxloaderpercentage/ajaxloaderpercentage.php"; ?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#frm-ajaxloaderpercentage').ajaxloaderpercentage(
					{
						loader:$('#ajaxloaderpercentage-loader'),
						width: '500px',
						height: '20px',
						rowtotal: 200,
						rowperprocess: 8,
						process: 'perf/perfGenerateDocument.php',
						callback_each: function(el, _res){},
						callback_full: function(el, _res){}
					}
				);
			});
		</script>
	</head>
	<body>
		<form id="frm-ajaxloaderpercentage">
			<?php for($i=1; $i<=200; $i++){ ?>
			<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
			<?php } ?>
			<input type="submit" name="op" value="Generate Document" />
		</form>
		<div id="ajaxloaderpercentage-loader"></div>
	</body>
</html>