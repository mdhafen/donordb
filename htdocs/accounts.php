<!DOCTYPE html>
<html>
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
<head>
<?php include 'head.php';?>
<script src="<?= $data['_config']['base_url'] ?>list.min.js"></script>
<script src="<?= $data['_config']['base_url'] ?>list.pagination.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-margin uk-margin-left">
<h2>Accounts</h2>
<div id="accounts_table_container">
<ul class="paginationTop"></ul>
<table class="" id="actions_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_account">Account Name</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'account')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_location">Site</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'location')">
  </th>
  <th>
    Notes<br>
    <input type="text" id="account_note_filter" size="10" onkeyup="do_filter(this.value,'note')">
  </th>
</tr>
</thead>
<tbody class="list">
<?php
   if ( !empty($data['accounts_list']) ) {
     foreach ( $data['accounts_list'] as $row ) {
?>
<tr id="accounts_<?= $row['accountid'] ?>">
<td class="list_account" data-list-cleanaccount="<?= urlencode($row['name']) ?>"><?= $row['name'] ?></td>
<td class="list_location"><?= $row['location_name'] ?></td>
<td class="list_note"><?= $row['note'] ?></td>
</tr>
<?php
     }
   }

?>
</tbody>
</table>
<ul class="paginationBottom"></ul>
</div>
<script>
function do_filter(value,field) {
// FIXME this still doesn't filter - try checking all three fields!
    if ( ! value ) {
        list_obj.filter();
    }
    else {
        list_obj.filter(function(item){
            var test = '';
            switch (field) {
                case 'account' : test = item.values().list_account; break;
                case 'location' : test = item.values().list_location; break;
                case 'note' : test = item.values().list_note; break;
            }
            return test.toLowerCase().indexOf(value.toLowerCase()) > -1;
        })
    }
}

var list_options = {
  valueNames: [
    { name: 'list_account', attr: 'data-list-cleanaccount' },
    'list_location','list_note'
  ],
  searchClass: 'list_search',
  sortClass: 'list_sort',
  //indexAsync: true,
  page: 15,
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

var list_obj = new List('accounts_table_container', list_options);
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
