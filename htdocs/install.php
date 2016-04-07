<!DOCTYPE html>
<html>
<head>
<?php $page_title='Installation'; include 'head.php';?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-margin uk-margin-left uk-margin-right">

<?php if ( !empty($data['error']) ) { ?>
<div class="uk-alert uk-alert-danger">
<?php if ( !empty($data['INSTALL_USER_EXTERNAL']) ) { ?><div>External Users is set.  No user information will be entered in this database, but this program will get it from another database.</div><?php } ?>

<?php if ( !empty($data['INSTALL_NO_TABLES']) ) { ?>
<div>The database hasn&apos;t been setup.  This program will now attempt to create the necessary tables for you.
  <?php if ( !empty($data['INSTALL_CREATESQL_CANT_READ']) ) { ?><br>Can&apos;t read the sql file.  You will have to do it yourself.<?php } ?>
  <?php if ( !empty($data['INSTALL_CREATETABLES_FAILED']) ) { ?><br>There was an error.  You will have to check on the state of the database and tables yourself.<?php } ?>
</div>
<?php } ?>

<?php if ( !empty($data['INSTALL_ALREADY_ADDED_ADMIN']) ) { ?><div>There are already users for managing the program.  This program won&apos;t create more for you.</div><?php } ?>

<?php if ( !empty($data['INSTALL_PASS_NOMATCH']) ) { ?><div>Passwords don&apos;t match.</div><?php } ?>
<?php if ( !empty($data['SET_INFO_USERNAME_USED']) ) { ?><div>That username is already in use.</div><?php } ?>
</div>
<?php } ?>

<?php if ( !empty($data['INSTALL_DONE']) ) { ?><div><a href="index.php">Installation is complete.  Please login to add users.</a></div><?php } ?>

	<div class="uk-flex uk-flex-middle uk-flex-center">
	<div class="uk-panel uk-panel-box">
		<h1>Please enter user information</h1>
		<div>This user will be the first system administrator, and will create further users.</div>
		<form class="uk-form" method="post" action="install.php">
			<fieldset class="uk-form-horizontal">
				<div class="uk-form-row">
					<label class="uk-form-label" for="locationid"></label>
					<div class="uk-form-controls"><input type="text" placeholder="Location Identifier" id="locationid" name="locationid"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="locationname"></label>
					<div class="uk-form-controls"><input type="text" placeholder="Location Name" id="locationname" name="locationname"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="username">Username</label>
					<div class="uk-form-controls"><input type="text" placeholder="username" id="username" name="username"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="password">Password</label>
					<div class="uk-form-controls"><input type="password" id="password" placeholder="password" name="password"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="password_confirm">Confirm Password</label>
					<div class="uk-form-controls"><input type="password" id="password_confirm" placeholder="password again" name="password_confirm"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="fullname"></label>
					<div class="uk-form-controls"><input type="text" placeholder="Full Name" id="fullname" name="fullname"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="email"></label>
					<div class="uk-form-controls"><input type="text" placeholder="email address" id="email" name="email"></div>
				</div>

				<div class="uk-form-row">
					<button class="uk-button uk-button-success uk-button-large">Submit</button>
				</div>
			</fieldset>
		</form>
	</div>
	</div>

</div>
<?php
include 'footer.php';
?>
