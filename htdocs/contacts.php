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
<div class="uk-container uk-margin uk-width-1-1">
<h2>Contacts</h2>
<div id="contacts_table_container">
<ul class="paginationTop"></ul>
<table class="uk-table" id="contacts_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_name">Name</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'name')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_company">Company</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'company')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_street">Street</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'street')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_city">City</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'city')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_state">State</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'state')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_zip">Zip</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'zip')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_phone">Phone</span><br>
    <input type="text" size="10" onkeyup="do_filter(this.value,'phone')">
  </th>
</tr>
</thead>
<tbody class="list">
<?php
   if ( !empty($data['contacts_list']) ) {
     foreach ( $data['contacts_list'] as $row ) {
?>
<tr id="contacts_<?= $row['contactid'] ?>">
<td class="list_name" data-list-cleanname="<?= urlencode($row['name']) ?>"><?= $row['name'] ?></td>
<td class="list_company" data-list-cleancompany="<?= urlencode($row['company']) ?>"><?= $row['company'] ?></td>
<td class="list_street"><?= $row['street'] ?></td>
<td class="list_city"><?= $row['city'] ?></td>
<td class="list_state"><?= $row['state'] ?></td>
<td class="list_zip"><?= $row['zip'] ?></td>
<td class="list_phone"><?= $row['phone'] ?></td>
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
                case 'name' : test = item.values().list_name; break;
                case 'company' : test = item.values().list_company; break;
                case 'street' : test = item.values().list_street; break;
            }
            return test.toLowerCase().indexOf(value.toLowerCase()) > -1;
        })
    }
}

var list_options = {
  valueNames: [
    { name: 'list_name', attr: 'data-list-cleanname' },
    { name: 'list_company', attr: 'data-list-cleancompany' },
    'list_street','list_city','list_state','list_zip','list_phone'
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

var list_obj = new List('contacts_table_container', list_options);
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
