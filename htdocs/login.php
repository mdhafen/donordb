<!DOCTYPE html>
<html>
<head>
<?php include 'head.php'; ?>
</head>
<body>
<div class="uk-container uk-margin-top">
	<div class="uk-flex uk-flex-middle uk-flex-center">
	<div class="uk-panel uk-panel-box">
		<h1><img class="uk-align-left" src="https://logos.washk12.org/wcsd_web_95.png" alt="WCSD">Foundation Donor Database</h1>
                <hr>
		<form class="uk-form" method="post" action="index.php">
<?php if ( !empty($data['NOTPERMITTED']) ) { ?>
			<div id="errors" class="uk-alert uk-alert-danger">
				<strong>You do not have access to that function.</strong>
			</div>
<?php } ?>
			<fieldset class="uk-form-horizontal">
				<div class="uk-form-row">
					<label class="uk-form-label" for="_username">Username</label>
					<div class="uk-form-controls"><input type="text" placeholder="username" id="_username" name="_username"></div>
				</div>

				<div class="uk-form-row">
					<label class="uk-form-label" for="_password">Password</label>
					<div class="uk-form-controls"><input type="password" id="_password" placeholder="password" name="_password"></div>
				</div>

				<div class="uk-form-row">
					<button class="uk-button uk-button-success uk-button-large">Login</button>
				</div>
			</fieldset>
		</form>
	</div>
	</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
