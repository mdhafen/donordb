<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
<script src="<?= $data['_config']['base_url'] ?>list.min.js"></script>
<script src="<?= $data['_config']['base_url'] ?>list.pagination.min.js"></script>
<style>
.paginationTop li, .paginationBottom li {
  display: inline-block;
  list-style: none;
  padding-right: 10px;
}

.paginationTop li.active a, .paginationBottom li.active a {
  color: #000;
}

.paginationTop li.disabled a, .paginationBottom li.disabled a,
.paginationTop li.disabled a:hover, .paginationBottom li.disabled a:hover {
  color: #000;
  cursor: default;
  text-decoration: none;
}

.list_sort:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid transparent;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}

.list_sort.asc:after {
  width: 0;
  height: 0;
  content:"";
  position: relative;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid #000;
  top:-15px;
  right:-5px;
}

.list_sort.desc:after {
  width: 0;
  height: 0;
  content:"";
  position: relative;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #000;
  top:13px;
  right:-5px;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-margin uk-container uk-width-1-1">
<?php if ( $data['op'] ) { ?>
<h2>Actions</h2>
<div id="actions_table_container">
<?php if ( empty($data['actions_list']) ) { ?>
  <div>No Actions found</div>
<?php } else { ?>
<ul class="paginationTop uk-display-inline-block"></ul>
<table class="uk-table" id="actions_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_date">Date</span><br>
    <input type="text" size="10" id="actions_filter_date" onkeyup="do_filter()">
  </th>
  <th>Amount</th>
  <th>
    <span class="list_sort" data-sort="list_contact_name">Contact Name</span><br>
    <input type="text" size="10" id="actions_filter_name" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_contact_company">Contact Company</span><br>
    <input type="text" size="10" id="actions_filter_company" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_account">Account Name</span><br>
    <input type="text" size="10" id="actions_filter_account" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_location">Site</span><br>
    <input type="text" size="10" id="actions_filter_location" onkeyup="do_filter()">
  </th>
  <th>
    Receipt<br>
    <input type="text" size="10" id="actions_filter_receipt" onkeyup="do_filter()">
  </th>
  <th>
    P.O.<br>
    <input type="text" size="10" id="actions_filter_po" onkeyup="do_filter()">
  </th>
  <th>
    Notes<br>
    <input type="text" size="10" id="actions_filter_note" onkeyup="do_filter()">
  </th>
  <th>Flags</th>
</tr>
</thead>
<tbody class="list">
<?php
   foreach ( $data['actions_list'] as $row ) {
?>
<tr id="actions_<?= $row['actionid'][0] ?>">
<td class="list_date" data-list-isodate="<?= $row['date'][0] ?>"><a href="editaction.php?actionid=<?= $row['actionid'][0] ?>" class="uk-button"><span class="uk-icon-pencil"></span></a> <a href="deleteaction.php?actionid=<?= $row['actionid'][0] ?>" class="uk-button"><span class="uk-icon-trash"></span></a> <?php for ( $i=0,$j=count($row['date']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. date('m/d/Y',strtotime($row['date'][$i])) .'</del></div>'; } else { print date('m/d/Y',strtotime($row['date'][$i])); } } ?></td>
<td class="list_amount"><?php for ( $i=0,$j=count($row['amount']); $i<$j; $i++ ) { ?><span class="<?= $row['amount'][$i] < 0 ? 'uk-text-danger' : '' ?>"><?php if ($i>0) { print '<div><del>'. number_format($row['amount'][$i],2) .'</del></div>'; } else { print number_format($row['amount'][$i],2); } ?></span><?php } ?></td>
<td class="list_contact_name"><?php for ( $i=0,$j=count($row['contact_name']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['contact_name'][$i] .'</del></div>'; } else { print $row['contact_name'][$i]; } } ?></td>
<td class="list_contact_company"><?php for ( $i=0,$j=count($row['company']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['company'][$i] .'</del></div>'; } else { print $row['company'][$i]; } } ?></td>
<td class="list_account"><?php for ( $i=0,$j=count($row['account_name']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['account_name'][$i] .'</del></div>'; } else { print $row['account_name'][$i]; } } ?></td>
<td class="list_location"><?php for ( $i=0,$j=count($row['location_name']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['location_name'][$i] .'</del></div>'; } else { print $row['location_name'][$i]; } } ?></td>
<td class="list_receipt"><?php for ( $i=0,$j=count($row['receipt']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['receipt'][$i] .'</del></div>'; } else { print $row['receipt'][$i]; } } ?></td>
<td class="list_po"><?php for ( $i=0,$j=count($row['po']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['po'][$i] .'</del></div>'; } else { print $row['po'][$i]; } } ?></td>
<td class="list_note"><?php for ( $i=0,$j=count($row['note']); $i<$j; $i++ ) { if ($i>0) { print '<div><del>'. $row['note'][$i] .'</del></div>'; } else { print $row['note'][$i]; } } ?></td>
<td class="list_flags" data-list-inkind="<?= !empty($row['in_kind'][0]) ? 'yes':'no' ?>" data-list-transfer="<?= !empty($row['is_transfer'][0]) ? 'yes':'no' ?>"><?php if ( !empty($row['in_kind'][0]) ) { ?><i title="In Kind" class="uk-icon-balance-scale"></i> <?php } ?><?php if ( !empty($row['is_transfer'][0]) ) { ?> <span title="Transfer" class="uk-text-nowrap"><i class="uk-icon-file-text-o"></i><i class="uk-icon-arrow-right"></i><i class="uk-icon-file-text-o"></i></span><?php } ?></td>
</tr>
<?php
   }
?>
</tbody>
</table>
<ul class="paginationBottom"></ul>
<script>
function do_filter() {
    var input = [
        document.getElementById('actions_filter_date').value,
        document.getElementById('actions_filter_name').value.toLowerCase(),
        document.getElementById('actions_filter_company').value.toLowerCase(),
        document.getElementById('actions_filter_account').value.toLowerCase(),
        document.getElementById('actions_filter_location').value.toLowerCase(),
        document.getElementById('actions_filter_receipt').value.toLowerCase(),
        document.getElementById('actions_filter_po').value.toLowerCase(),
        document.getElementById('actions_filter_note').value.toLowerCase(),
    ];
    list_obj.filter(function(item){
        var match= [
            item.values().list_date,
            decodeURIComponent((item.values().list_contact_name+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            decodeURIComponent((item.values().list_contact_company+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            decodeURIComponent((item.values().list_account+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            item.values().list_location.toLowerCase(),
            item.values().list_receipt.toLowerCase(),
            item.values().list_po.toLowerCase(),
            item.values().list_note.toLowerCase(),
        ];
        return ( match[0].indexOf(input[0]) > -1 && match[1].indexOf(input[1]) > -1 && match[2].indexOf(input[2]) > -1 && match[3].indexOf(input[3]) > -1 && match[4].indexOf(input[4]) > -1 && match[5].indexOf(input[5]) > -1 && match[6].indexOf(input[6]) > -1 && match[7].indexOf(input[7]) > -1 );
    })
}

var list_options = {
  valueNames: [
    { name: 'list_date', attr: 'data-list-isodate' },
    'list_amount','list_contact_name','list_contact_company','list_account',
    'list_location','list_receipt','list_po','list_note'
  ],
  searchClass: 'list_search',
  sortClass: 'list_sort',
  //indexAsync: true,
  page: 10,
  plugins: [
    ListPagination({
      name: "paginationTop",
      paginationClass: "paginationTop",
      innerWindow: 2,
      outerWindow: 1
    }),
    ListPagination({
      name: "paginationBottom",
      paginationClass: "paginationBottom",
      innerWindow: 2,
      outerWindow: 1
    })
  ]
};

var list_obj = new List('actions_table_container', list_options);
</script>
<?php } ?>
</div>
<?php } else { ?>
<h2>Search Action Changes</h2>
<form class="uk-form" method="post" action="modlog_actions.php">
    <input type="hidden" name="op" value="search">
    <div class="uk-flex uk-flex-wrap">
    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_accountid">Previous Account</label>
            <div class="uk-form-controls">
                <input type="text" id="acc_id1" name="acc_id1" value="" onkeyup="select_field_filter_byValNText('old_accountid',this.value)" tabindex="1">
                <select id="old_accountid" name="old_account" onchange="input_update('acc_id1',this.value)" tabindex="8">
                    <option value="">Select Previous Account</option>
<?php foreach ( $data['accounts'] as $acc ) { ?>
                    <option value="<?= $acc['accountid'] ?>"<?= !empty($acc['selected']) ? " selected" : "" ?>><?= $acc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_accountid">New Account</label>
            <div class="uk-form-controls">
                <input type="text" id="acc_id2" name="acc_id2" value="" onkeyup="select_field_filter_byValNText('new_accountid',this.value)" tabindex="1">
                <select id="new_accountid" name="new_account" onchange="input_update('acc_id2',this.value)" tabindex="8">
                    <option value="">Select new Account</option>
<?php foreach ( $data['accounts'] as $acc ) { ?>
                    <option value="<?= $acc['accountid'] ?>"<?= !empty($acc['selected']) ? " selected" : "" ?>><?= $acc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_contactid">Previous Contact</label>
            <div class="uk-form-controls">
                <input type="text" id="con_id1" name="con_id1" value="" onkeyup="select_field_filter_byValNText('old_contactid',this.value)" tabindex="2">
                <select id="old_contactid" name="old_contact" tabindex="9">
                    <option value="">Select Previous Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"<?= !empty($con['selected']) ? " selected" : "" ?>><?= $con['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_contactid">New Contact</label>
            <div class="uk-form-controls">
                <input type="text" id="con_id2" name="con_id2" value="" onkeyup="select_field_filter_byValNText('new_contactid',this.value)" tabindex="2">
                <select id="new_contactid" name="new_contact" tabindex="9">
                    <option value="">Select New Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"<?= !empty($con['selected']) ? " selected" : "" ?>><?= $con['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_locationid">Previous Location</label>
            <div class="uk-form-controls">
                <input type="number" id="loc_id1" name="loc_id1" value="" onkeyup="select_field('old_locationid',this.value)" tabindex="3">
                <select id="old_locationid" name="old_location" onchange="input_update('loc_id1',this.value); location_changed(this.value,'old_accountid');" tabindex="10">
                    <option value="">Select Previous Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_locationid">New Location</label>
            <div class="uk-form-controls">
                <input type="number" id="loc_id2" name="loc_id2" value="" onkeyup="select_field('new_locationid',this.value)" tabindex="3">
                <select id="new_locationid" name="new_location" onchange="input_update('loc_id2',this.value); location_changed(this.value,'new_accountid');" tabindex="10">
                    <option value="">Select New Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_amount">Previous Amount</label>
            <div class="uk-form-controls">
                $<input type="number" step="0.01" id="old_amount" name="old_amount" value="" tabindex="4">
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_amount">New Amount</label>
            <div class="uk-form-controls">
                $<input type="number" step="0.01" id="new_amount" name="new_amount" value="" tabindex="4">
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_receipt">Previous receipt</label>
            <div class="uk-form-controls">
                <input type="text" id="old_receipt" name="old_receipt" value="" tabindex="5">
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_receipt">New receipt</label>
            <div class="uk-form-controls">
                <input type="text" id="new_receipt" name="new_receipt" value="" tabindex="5">
            </div>
        </div>

    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_po">Previous P.O.</label>
            <div class="uk-form-controls">
                <input type="text" id="old_po" name="old_po" value="" tabindex="6">
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_po">New P.O.</label>
            <div class="uk-form-controls">
                <input type="text" id="new_po" name="new_po" value="" tabindex="6">
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="old_date">Previous Action Date</label>
            <div class="uk-form-controls">
                <input type="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" id="old_date" name="old_date" value="" tabindex="7"> <span class="uk-form-help-inline">(yyyy-mm-dd)</span>
            </div>
        </div>

        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="new_date">New Action Date</label>
            <div class="uk-form-controls">
                <input type="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" id="new_date" name="new_date" value="" tabindex="7"> <span class="uk-form-help-inline">(yyyy-mm-dd)</span>
            </div>
        </div>
    </fieldset>

    <fieldset class="uk-form-stacked uk-margin-bottom uk-margin-right">
        <div class="uk-form-row uk-panel-box uk-panel">
            <label class="uk-form-label" for="mod_date">Date Changed</label>
            <div class="uk-form-controls">
                <input type="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" id="mod_date" name="mod_date" value="" tabindex="8"> <span class="uk-form-help-inline">(yyyy-mm-dd)</span>
            </div>
        </div>
    </fieldset>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-controls">
             <input class="uk-button" type="submit" name="op" value="Search" tabindex="9">
        </div>
    </div>
</form>

<script>
function select_field(field,value) {
    var el_field = document.getElementById(field);

    for ( var i = 0; i < el_field.options.length; i++ ) {
        if ( el_field.options[i].value == value ) {
            el_field.value = el_field.options[i].value;
            if ( "createEvent" in document ) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change",false,true);
                el_field.dispatchEvent(evt);
            } else el_field.fireEvent("onchange");
            break;
        }
    }
}

function select_field_filter_byValNText(field,value) {
    var el_field = document.getElementById(field);
    value = value.toLowerCase();
    var found = -1;
    var num_found = 0;

    if ( value ) {
        for ( var i = 0; i < el_field.options.length; i++ ) {
            var this_opt = el_field.options[i];
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
    }
    else {
        for ( var i = 0; i < el_field.options.length; i++ ) {
            var this_opt = el_field.options[i];
            if ( this_opt.data_old_display && this_opt.data_old_display != 'none' ) {
                this_opt.style.display = this_opt.data_old_display;
            }
            else {
                this_opt.style.display = '';
            }
        }
        el_field.value = "0";
    }
    if ( found >= 0 ) {
        el_field.value = el_field.options[found].value;
        if ( num_found == 1 ) {
            if ( "createEvent" in document ) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change",false,true);
                el_field.dispatchEvent(evt);
            } else el_field.fireEvent("onchange");
        }
        if ( field == 'old_locationid' || field == 'new_locationid' ) {
            location_changed(el_field.value, field=='old_locationid'?'old_accountid':'new_accountid');
        }
    }
}

function input_update(field,value) {
    var el_field = document.getElementById(field);
    el_field.value = value;
}

function location_changed(value,select_id) {
    var modal = UIkit.modal.blockUI("Loading Accounts...");
    var data = {};
    if ( value ) {
        data.locationid = value;
    }
    $.post('<?= $data['_config']['base_url'] ?>api/get_accounts.php', data, function(xml_result) { update_account(select_id,xml_result,modal) }, "xml" );
}

function update_account(select_id,xml_result,modal) {
    var el = document.getElementById(select_id);
    while ( el.lastChild && el.lastChild != el.firstChild ) { el.removeChild(el.lastChild); }
    if ( $(xml_result).find("state").text() == 'Success' ) {
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
        modal = UIkit.modal.alert("Could load accounts for the selected location.  Sorry for the inconvenience.  Maybe reloading this page will help.");
    }
}
</script>
<?php } ?>
</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
