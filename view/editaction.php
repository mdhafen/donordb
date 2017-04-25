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
            <div class="uk-form-controls"><input type="number" step="0.01" id="amount" name="amount" value="<?= (!empty($data['action']['amount'])) ? $data['action']['amount'] : "0" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="contactid">Contact</label>
            <div class="uk-form-controls">
                <input type="text" id="con_id" name="con_id" value="<?= (!empty($data['action']['contactid'])) ? $data['action']['contactid'] : "" ?>" onkeyup="select_contact(this.value)">
                <select id="contactid" name="contactid">
                    <option value="">Select Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"<?= !empty($con['selected']) ? " selected" : "" ?>><?= $con['name'] ?></option>
<?php } ?>
                </select>
                <span class="uk-form-help-inline"><a href="#" id="new_contact_button" class="uk-button" data-uk-modal="{target:'#modal_new_contact',center:true,bgclose:false}">New Contact...</a></span>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="locationid">Location</label>
            <div class="uk-form-controls">
                <input type="text" id="loc_id" name="loc_id" value="<?= (!empty($data['action']['locationid'])) ? $data['action']['locationid'] : "" ?>" onkeyup="select_location(this.value)">
                <select id="locationid" name="locationid" onchange="location_changed()">
                    <option value="">Select Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['locationid'] ?> - <?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="accountid">Account</label>
            <div class="uk-form-controls">
                <input type="number" id="acc_id" name="acc_id" value="<?= (!empty($data['action']['accountid'])) ? $data['action']['accountid'] : "" ?>" onkeyup="select_account(this.value)">
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
            <label class="uk-form-label" for="in_kind">In Kind</label>
            <div class="uk-form-controls"><input type="checkbox" id="in_kind" name="in_kind"<?= (!empty($data['action']['in_kind'])) ? " checked" : "" ?>></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="is_transfer">Is A Transfer</label>
            <div class="uk-form-controls"><input type="checkbox" id="is_transfer" name="is_transfer"<?= (!empty($data['action']['is_transfer'])) ? " checked" : "" ?>></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Save">
                 <input class="uk-button" type="button" name="op" value="Delete"onclick="document.location='deleteaction.php?actionid=<?= $data['actionid'] ?>'">
            </div>
        </div>

    </fieldset>
</form>
</div>

<!-- New contact Modal -->
<div id="modal_new_contact" class="uk-modal">
    <div class="uk-modal-dialog uk-modal-dialog-large">
        <a class="uk-modal-close uk-close"></a>
        <div class="uk-modal-header">
            <h2>New Contact</h2>
        </div>
        <div id="new_contact_errors" class="uk-alert uk-alert-warning uk-hidden" data-uk-alert>
            <a href="" class="uk-alert-close uk-close"></a>
            <h2>There was an error!</h2>
        </div>
        <form class="uk-form" method="post" action="editcontact.php">
            <fieldset class="uk-form-horizontal">
                <div class="uk-form-row">
                    <label class="uk-form-label" for="name">Name</label>
                    <div class="uk-form-controls"><input type="text" id="name" name="name" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="company">Company</label>
                    <div class="uk-form-controls"><input type="text" id="company" name="company" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="street">Street</label>
                    <div class="uk-form-controls"><input type="text" id="street" name="street" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="city">City</label>
                    <div class="uk-form-controls"><input type="text" id="city" name="city" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="state">State</label>
                    <div class="uk-form-controls"><input type="text" id="state" name="state" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="zip">Zip</label>
                    <div class="uk-form-controls"><input type="text" id="zip" name="zip" value=""></div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="phone">Phone</label>
                    <div class="uk-form-controls"><input type="text" id="phone" name="phone" value=""></div>
                </div>

                <div class="uk-form-row">
                    <div class="uk-form-controls">
                        <input type="button" class="uk-button" value="Save" name="op" onclick="save_contact();">
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>

<script>
$('#modal_new_contact').on({
  'hide.uk.modal': function(){
        $('#modal_new_contact input[type=text]').val('');
        $('#new_contact_errors').addClass('uk-hidden');
        $('#new_contact_errors div').remove();
  }
});

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
    if ( $(xml_result).find(" > result > state").text() == 'Success' ) {
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

function select_contact(value) {
    var el_contact = document.getElementById('contactid');
    value = value.toLowerCase();
    var found = -1;

    for ( var i = 0; i < el_contact.options.length; i++ ) {
        var this_opt = el_contact.options[i];
        var text = this_opt.text.toLowerCase();
        var val = this_opt.value;
        if ( text.indexOf(value) > -1 || value == val ) {
            if ( found < 0 ) { found = i; }
            if ( this_opt.style.display == 'none' ) {
                if ( this_opt.data_old_display ) {
                    this_opt.style.display = this_opt.data_old_display;
                }
                else {
                    this_opt.style.display = '';
                }
            }
        }
        else {
            if ( ! this_opt.data_old_display && this_opt.style.display != 'none' ) {
                this_opt.data_old_display = this_opt.style.display;
            }
            this_opt.style.display = 'none';
        }
    }
    if ( found >= 0 ) {
        el_contact.value = el_contact.options[found].value;
    }
}

function select_location(value) {
    var el_location = document.getElementById('locationid');
    value = value.toLowerCase();
    var found = -1;
    var num_found = 0;

    for ( var i = 0; i < el_location.options.length; i++ ) {
        var this_opt = el_location.options[i];
        var text = this_opt.text.toLowerCase();
        var val = this_opt.value;
        if ( text.indexOf(value) > -1 || value == val ) {
            num_found++;
            if ( found < 0 ) { found = i; }
            if ( this_opt.style.display == 'none' ) {
                if ( this_opt.data_old_display ) {
                    this_opt.style.display = this_opt.data_old_display;
                }
                else {
                    this_opt.style.display = '';
                }
            }
        }
        else {
            if ( ! this_opt.data_old_display && this_opt.style.display != 'none' ) {
                this_opt.data_old_display = this_opt.style.display;
            }
            this_opt.style.display = 'none';
        }
    }
    if ( found >= 0 ) {
        el_location.value = el_location.options[found].value;
        if ( num_found == 1 ) {
            if ( "createEvent" in document ) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change",false,true);
                el_location.dispatchEvent(evt);
            } else el_location.fireEvent("onchange");
        }
    }
}

function select_account(accountid) {
    var el_account = document.getElementById('accountid');

    for ( var i = 0; i < el_account.options.length; i++ ) {
        if ( el_account.options[i].value == accountid ) {
            el_account.value = el_account.options[i].value;
            break;
        }
    }
}

function save_contact() {
    var contact_info = {};
    $('#modal_new_contact input[type=text]').each(function(){
        contact_info[this.name] = this.value;
    });
    $.post('<?= $data['_config']['base_url'] ?>api/save_contact.php', contact_info, function(xml_result) { contact_saved(xml_result) }, "xml" );
}

function contact_saved(xml_result) {
    if ( $(xml_result).find(" > result > state").text() == 'Success' ) {
        var contactid = $(xml_result).find("contactid").text();
        var modal = UIkit.modal('#modal_new_contact');
        modal.hide();
        get_contacts(contactid);
    }
    else {
        var el = documentGetElementById('new_contact_errors');
        $(xml_result).find("messages").each(function(){
            var mess = document.createElement('div');
            mess.appendChild( document.createTextNode(this.message) );
            el.appendChild( mess );
        });
        $('#new_contact_errors').removeClass('uk-hidden');
    }
}

function get_contacts(selected) {
    var modal = UIkit.modal.blockUI("Reloading Contacts...");
    $.post('<?= $data['_config']['base_url'] ?>api/get_contacts.php', null, function(xml_result) { update_contacts(xml_result,modal,selected) }, "xml" );
}

function update_contacts(xml_result,modal,selected) {
    var el = document.getElementById('contactid');
    while ( el.lastChild && el.lastChild != el.firstChild ) { el.removeChild(el.lastChild); }
    if ( $(xml_result).find(" > result > state").text() == 'Success' ) {
        $(xml_result).find("contact").each( function(){
            var contactid = $(this).find('contactid').text();
            var contact_name = $(this).find('name').text();
            var opt = document.createElement('option');
            opt.value = contactid;
            opt.appendChild( document.createTextNode(contact_name) );
            el.appendChild( opt );
        });
        if ( selected ) {
            el.value = selected;
        }
        modal.hide();
    }
    else {
        modal.hide();
        modal = UIkit.modal.alert("Could not reload contacts.  Sorry for the inconvenience.  Maybe reloading this page will help.");
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
