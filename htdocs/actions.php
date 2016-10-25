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
<ul class="paginationTop uk-display-inline-block"></ul> <span class="uk-padding-left"><a href="<?= $data['_config']['base_url'] ?>allactions.php">All Actions</a></span>
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
</tr>
</thead>
<tbody class="list">
<?php
   foreach ( $data['actions_list'] as $row ) {
?>
<tr id="actions_<?= $row['actionid'] ?>">
<td class="list_date" data-list-isodate="<?= $row['date'] ?>"><a href="editaction.php?actionid=<?= $row['actionid'] ?>" class="uk-button"><span class="uk-icon-pencil"></span></a> <?= date('m/d/Y',strtotime($row['date'])) ?></td>
<td class="list_amount"><span class="<?= $row['amount'] < 0 ? 'uk-text-danger' : '' ?>"><?= number_format($row['amount'],2) ?></span></td>
<td class="list_contact_name"><?= $row['contact_name'] ?></td>
<td class="list_contact_company"><?= $row['company'] ?></td>
<td class="list_account"><?= $row['account_name'] ?></td>
<td class="list_location"><?= $row['location_name'] ?></td>
<td class="list_receipt"><?= $row['receipt'] ?></td>
<td class="list_po"><?= $row['po'] ?></td>
<td class="list_note"><?= $row['note'] ?></td>
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
<h2>Search Actions</h2>
<div class="uk-grid">
<form class="uk-form uk-width-1-4" method="post" action="actions.php">
    <input type="hidden" name="op" value="search">
    <input type="hidden" name="field" value="account">
    <fieldset class="uk-form-stacked">

        <div class="uk-form-row">
            <label class="uk-form-label" for="accountid">Account</label>
            <div class="uk-form-controls">
                <input type="number" id="acc_id" name="acc_id" value="" onkeyup="select_field('accountid',this.value)">
                <select id="accountid" name="term" onchange="input_update('acc_id',this.value)">
                    <option value="">Select Account</option>
<?php foreach ( $data['accounts'] as $acc ) { ?>
                    <option value="<?= $acc['accountid'] ?>"<?= !empty($acc['selected']) ? " selected" : "" ?>><?= $acc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Search">
            </div>
        </div>
    </fieldset>
</form>

<form class="uk-form uk-width-1-4" method="post" action="actions.php">
    <input type="hidden" name="op" value="search">
    <input type="hidden" name="field" value="contact">
    <fieldset class="uk-form-stacked">

        <div class="uk-form-row">
            <label class="uk-form-label" for="contactid">Contact</label>
            <div class="uk-form-controls">
                <input type="number" id="con_id" name="con_id" value="" onkeyup="select_field('contactid',this.value)">
                <select id="contactid" name="term" onchange="input_update('con_id',this.value)">
                    <option value="">Select Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"<?= !empty($con['selected']) ? " selected" : "" ?>><?= $con['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Search">
            </div>
        </div>
    </fieldset>
</form>

<form class="uk-form uk-width-1-4" method="post" action="actions.php">
    <input type="hidden" name="field" value="location">
    <fieldset class="uk-form-stacked">

        <div class="uk-form-row">
            <label class="uk-form-label" for="locationid">Location</label>
            <div class="uk-form-controls">
                <input type="number" id="loc_id" name="loc_id" value="" onkeyup="select_field('locationid',this.value)">
                <select id="locationid" name="term" onchange="input_update('loc_id',this.value); location_changed();">
                    <option value="">Select Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>
        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Search">
            </div>
        </div>
    </fieldset>
</form>

<form class="uk-form uk-width-1-4" method="post" action="actions.php">
    <input type="hidden" name="op" value="search">
    <input type="hidden" name="field" value="date">
    <fieldset class="uk-form-stacked">

        <div class="uk-form-row">
            <label class="uk-form-label" for="date">Actions Since</label>
            <div class="uk-form-controls">
                <input type="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" id="date" name="term" value=""> <span class="uk-form-help-inline">(yyyy-mm-dd)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Search">
            </div>
        </div>
    </fieldset>
</form>
</div>

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

function input_update(field,value) {
    var el_field = document.getElementById(field);
    el_field.value = value;
}

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
</script>
<?php } ?>
</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
