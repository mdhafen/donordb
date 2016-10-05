<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-container uk-margin uk-width-1-1">
<h2><?= empty($data['action']['actionid']) ? "New" : "Edit" ?> Action</h2>

<?php if ( $data['op'] == 'SaveSuccess' ) { ?>
<div class="uk-alert uk-alert-success">
     Save Successful!
</div>
<?php } ?>

<form class="uk-form" method="post" action="editaction.php">
    <input type="hidden" name="actionid" value="<?= $data['actionid'] ?>">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <label class="uk-form-label" for="date">Date</label>
            <div class="uk-form-controls"><input type="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" id="date" name="date" value="<?= (!empty($data['action']['date'])) ? $data['action']['date'] : "" ?>"> <span class="uk-form-help-inline">(yyyy-mm-dd)</span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="amount">Amount</label>
            <div class="uk-form-controls"><input type="number" id="amount" name="amount" value="<?= (!empty($data['action']['amount'])) ? $data['action']['amount'] : "0" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="contactid">Contact</label>
            <div class="uk-form-controls">
                <select id="contactid" name="contactid">
                    <option value="">Select Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"<?= !empty($con['selected']) ? " selected" : "" ?>><?= $con['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="locationid">Location</label>
            <div class="uk-form-controls">
                <select id="locationid" name="locationid" onchange="location_changed()">
                    <option value="">Select Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="accountid">Account</label>
            <div class="uk-form-controls">
                <input type="number" id="acc_id" name="acc_id" value="" onkeyup="select_account(this.value)">
                <select id="accountid" name="accountid">
                    <option value="">Select Account</option>
<?php foreach ( $data['accounts'] as $acc ) { ?>
                    <option value="<?= $acc['accountid'] ?>"<?= !empty($acc['selected']) ? " selected" : "" ?>><?= $acc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="receipt">Receipt</label>
            <div class="uk-form-controls"><input type="text" id="receipt" name="receipt" value="<?= (!empty($data['action']['receipt'])) ? $data['action']['receipt'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="po">Po</label>
            <div class="uk-form-controls"><input type="text" id="po" name="po" value="<?= (!empty($data['action']['po'])) ? $data['action']['po'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="note">Note</label>
            <div class="uk-form-controls"><textarea id="note" name="note"><?= (!empty($data['action']['note'])) ? $data['action']['note'] : "" ?></textarea></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Save">
            </div>
        </div>

    </fieldset>
</form>
</div>

<script>
function location_changed() {
    var loc = document.getElementById('locationid').value
    if ( loc ) {
        var modal = UIkit.modal.blockUI("Loading Accounts...");
        $.post('<?= $data['_config']['base_url'] ?>api/get_accounts_at_location.php', {"locationid" : loc}, function(xml_result) { update_account(xml_result,modal) }, "xml" );
    }
}

function update_account(xml_result,modal) {
    var el = document.getElementById('accountid');
    while ( el.lastChild && el.lastChild != el.firstChild ) { el.removeChild(el.lastChild); }
    if ( $(xml_result).find("state").text() == 'Success' ) {
        $(xml_result).find("account").each( function(){
            var accountid = $(this).find('accountid').text();
            var account_name = $(this).find('name').text();
            var opt = document.createElement('option');
            opt.value = accountid;
            opt.appendChild( document.createTextNode(account_name) );
            el.appendChild( opt );
        });
        modal.hide();
    }
    else {
        modal.hide();
        modal = UIkit.modal.alert("Could load accounts for the selected location.  Sorry for the inconvenience.  Maybe reloading this page will help.");
    }
}

function select_account(accountid) {
    var el_account = document.getElementById('accountid');

    for ( var i = 0; i < el_account.options.length; i++ ) {
        if ( el_account.options[i].value == accountid ) {
            el_account.value = i;
            break;
        }
    }
}
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
