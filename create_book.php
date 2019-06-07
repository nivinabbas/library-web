<?php include('../functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL - Create book</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=register_btn] {
			background: #003366;
		}
	</style>
</head>
<body>
	<div class="header">
		<h2>Admin - create book</h2>
	</div>
	
	<form method="post" action="create_book.php">
    <?php echo display_error(); ?>

		<div class="input-group">
			<label>serialNo</label>
			<input type="text" name="serialNo" value="<?php echo $serialNo; ?>">
		</div>
		<div class="input-group">
			<label>Name</label>
			<input type="name" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label>Category</label>
			<select name="category" id="category" >
				<option value=""></option>
				<option value="Engineering">Engineering</option>
				<option value="Medical">Medical</option>
                <option value="Sport">Sport</option>
			</select>
		</div>
		<div class="input-group">
			<label>Reserved</label>
			<input type="checkbox" name="reserved" value="<?php echo $reserved; ?>">
		</div>
	</form>
</body>
</html>