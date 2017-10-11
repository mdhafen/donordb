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
<h2>Transfer funds to another Account</h2>

<form class="uk-form" method="post" action="account_action.php">
    <input type="hidden" name="accountid" value="<?= $data['accountid'] ?>">
    <input type="hidden" name="op" value="transfer">
    <input type="hidden" name="action" value="do_it">

    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <label class="uk-form-label" for="contactid">Contact</label>
            <div class="uk-form-controls">
                <input type="number" id="con_id" name="con_id" value="" onkeyup="select_contact(this.value)">
                <select id="contactid" name="contactid">
                    <option value="">Select Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"><?= $con['name'] ?></option>
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
            <label class="uk-form-label" for="to_accountid">Account</label>
            <div class="uk-form-controls">
                <input type="number" id="acc_id" name="acc_id" value="<?= (!empty($data['action']['accountid'])) ? $data['action']['accountid'] : "" ?>" onkeyup="select_account(this.value)">
                <select id="to_accountid" name="to_accountid">
                    <option value="">Select Account</option>
<?php foreach ( $data['accounts'] as $acc ) { ?>
                    <option value="<?= $acc['accountid'] ?>"<?= !empty($acc['selected']) ? " selected" : "" ?>><?= $acc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="amount">Amount</label>
            <div class="uk-form-controls"><input type="text" id="amount" name="amount" value="<?= (!empty($data['balance'])) ? $data['balance'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="note">Note</label>
            <div class="uk-form-controls"><textarea id="note" name="note"></textarea></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" value="Transfer">
            </div>
        </div>

    </fieldset>
</form>
</div>

<script>
function select_contact(contactid) {
    var el_contact = document.getElementById('contactid');

    for ( var i = 0; i < el_contact.options.length; i++ ) {
        if ( el_contact.options[i].value == contactid ) {
            el_contact.value = el_contact.options[i].value;
            break;
        }
    }
}

function select_account(accountid) {
    var el_account = document.getElementById('to_accountid');

    for ( var i = 0; i < el_account.options.length; i++ ) {
        if ( el_account.options[i].value == accountid ) {
            el_account.value = el_account.options[i].value;
            break;
        }
    }
}

function location_changed() {
    var loc = document.getElementById('locationid').value;
    var data = {};
    if ( loc ) {
        data.locationid = loc;
    }
    var modal = UIkit.modal.blockUI("Loading Accounts...");
    $.post('<?= $data['_config']['base_url'] ?>api/get_accounts.php', data, function(xml_result) { update_account(xml_result,modal) }, "xml" );
    }
}

function update_account(xml_result,modal) {
    var el = document.getElementById('to_accountid');
    while ( el.lastChild && el.lastChild != el.firstChild ) { el.removeChild(el.lastChild); }
    if ( $(xml_result).find(" > result > state").text() == 'Success' ) {
        var opt = document.createElement('option');
        opt.value = "0";
        opt.appendChild( document.createTextNode("Select Account") );
        el.appendChild( opt );
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
        modal = UIkit.modal.alert("Could not load accounts for the selected location.  Sorry for the inconvenience.  Maybe reloading this page will help.");
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
