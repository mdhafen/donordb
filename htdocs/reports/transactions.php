<!DOCTYPE html>
<html>
<head>
<?php include $data['_config']['base_dir'].'/htdocs/head.php';?>
<style>
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

thead tr, tbody tr {
    border: solid 1px gray;
}

.report_body_header {
    background-color: black;
    color: white;
}

thead.report_multi_body::before {
    content: "";
    display: table-row;
    height: 30px;
}
</style>
<script src="<?= $data['_config']['base_url'] ?>list.min.js"></script>
<script src="<?= $data['_config']['base_url'] ?>list.pagination.min.js"></script>
</head>
<body>
<?php include $data['_config']['base_dir'].'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include $data['_config']['base_dir'].'/htdocs/menu.php'; ?>
<div class="uk-margin uk-margin-left uk-container uk-width-1-1">
  <h2><?= $data['report_title'] ?></h2>

<?php if ( !empty($data['op']) ) { ?>
    <div id="reports_table_container">
      <table class="uk-table" id="report_table">
<?php
   if ( !empty($data['report_body']) ) {
     foreach ( $data['report_body'] as $account ) {
       print "<thead class='list report_multi_body'>\n<tr><th colspan='7' class='report_body_header' list_note>${account['name']}</th></tr>\n";

       if ( !empty($data['report_header']) ) {
           print "<tr>";
           foreach ( $data['report_header'] as $row ) {
               print "<th>${row['column_title']}</th>";
           }
           print "</tr>\n";
       }

       print "</thead>\n<tbody class='list'>\n";
       print "<tr>\n<td colspan='6' list_note>Calculated Previous Balance</td>\n<td list_amount>${account['previous']}</td>\n</tr>\n";
       foreach ( $account['rows'] as $row ) {
?>
        <tr>
<?php
         foreach ( $row as $column ) {
?>
          <td<?= !empty($column['width']) ? " colspan='${column['width']}'" : "" ?><?= !empty($column['clean_value']) ? " data-list-${column['column_name']}='${column['clean_value']}'" : "" ?><?= !empty($column['column_name']) ? " list_${column['column_name']}" : "" ?>><?= !empty($column['link']) ? "<a href='${column['link']}'>" : "" ?><?= $column['value'] ?><?= !empty($column['link']) ? "</a>" : "" ?></td>
<?php
         }
       }
       print "<tr>\n<td colspan='6' class='report_body_header' list_note>Total</td>\n<td class='report_body_header' list_amount>${account['total']}</td>\n</tr>\n</tbody>\n";
?>
        </tr>
<?php
     }
   }
?>
    </table>
  </div>
<script>
var list_options = {
  valueNames: [ <?php 
   if ( !empty($data['report_header']) ) {
     $columns = array();
     foreach ( $data['report_header'] as $row ) {
         $columns[] = !empty($row['clean_attr']) ? "{ name: 'list_${row['column_name']}', attr: 'data-list-${row['column_name']}' }" : "'list_${row['column_name']}'";
     }
     print implode( ',', $columns );
   }
?>
  ],
  searchClass: 'list_search',
  sortClass: 'list_sort',
  //indexAsync: true,
};

var list_obj = new List('reports_table_container', list_options);
</script>
<?php
} else {
    if ( !empty($data['params']) ) {
      $count = 1;
?>
  <form class="uk-form" method="post">
    <input type="hidden" name="op" value="do_it">
    <fieldset class="uk-form-horizontal">
<?php foreach ( $data['params'] as $row ) { ?>
      <div class="uk-form-row">
<?php
      switch( $row['type'] ) {
        case 'input' :
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
        <div class="uk-form-controls">
          <input type="text" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['pattern']) ? " pattern='${row['pattern']}'" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
        </div>
<?php
          break;
        case 'select' :
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
        <div class="uk-form-controls">
          <select id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>"<?= !empty($row['size']) ? " size='${row['size']}'" : "" ?><?= !empty($row['multiple']) ? " multiple" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?= !empty($row['first_blank']) ? "<option value=''></option>\n" : "" ?>
<?php foreach ( $row['option_loop'] as $option ) { ?>
            <option value="<?= $option['value'] ?>"<?= !empty($option['selected']) ? " selected" : "" ?>><?= $option['label'] ?></option>
<?php } ?>
          </select>
        </div>
<?php
          break;
        case 'check' :
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
        <div class="uk-form-controls">
          <input type="checkbox" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['checked']) ? " checked" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
        </div>
<?php
          break;
        case 'radio' :
          print "            <span class='uk-form-label'>${row['label']}</span>\n";
          foreach ( $row['option_loop'] as $option ) {
?>
        <div class="uk-form-controls">
          <label class=""><?= $option['label'] ?>
            <input type="radio" id="<?= !empty($row['id']) ? $row['id'] .'_'. $count++ : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($option['value']) ? $option['value'] : "" ?>"<?= !empty($option['selected']) ? " checked" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
          </label><br>
        </div>
<?php
          }
          break;
        case 'number' :
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
        <div class="uk-form-controls">
          <input type="number" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['min']) ? " min='${row['min']}'" : "" ?><?= !empty($row['max']) ? " max='${row['max']}'" : "" ?><?= !empty($row['step']) ? " step='${row['step']}'" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
        </div>
<?php
          break;
        case 'date' :
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
        <div class="uk-form-controls">
          <input type="date" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" data-uk-datepicker="{format:'YYYY-MM-DD'}" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
        </div>
<?php   } ?>
      </div>
<?php } ?>
      <div class="uk-form-row uk-form-controls">
        <input type="submit" value="Run Report" class="uk-button">
      </div>
    </fieldset>
  </form>
  <script>
      var accts = [
<?php
      $last = end($data['all_accounts']);
      foreach ( $data['all_accounts'] as $acct ) { ?>
          { 'locationid' : '<?= $acct['locationid'] ?>', 'accountid' : '<?= $acct['accountid'] ?>', 'name' : '<?= $acct['name'] ?>' }<?= $acct == $last ? '' : ',' ?>
<?php } ?>
      ];
      function update_account_select(locId) {
          var modal = UIkit.modal.blockUI("Reloading Accounts...");
          var el = document.getElementById('account_select');
          while ( el.lastChild && el.lastChild != el.firstChild ) { el.removeChild(el.lastChild); }
          for ( var opt in accts ) {
              if ( opt.locationid == locId ) {
                  var new_opt = document.createElement('option');
                  new_opt.value = opt.accountid;
                  new_opt.appendChild( document.createTextNode( opt.name ) );
                  el.appendChild( new_opt );
              }
          }
          modal.hide();
      }
  </script>
<?php   } ?>
<?php } ?>
</div>
</div>
</div>
<?php include $data['_config']['base_dir'].'/htdocs/footer.php'; ?>

</body>
</html>
