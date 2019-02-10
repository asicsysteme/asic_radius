
echo '<ul class="dropdown-menu dropdown-menu-right">';
$%model% = new M%model%();
$%model%->id_%model% = Mreq::tp('id');
$%model%->get_%model%();



$action = new TableTools();
$action->line_data = $%model%->%model%_info;
$action->action_line_table('%model%', '%table%', $%model%->%model%_info['creusr'], 'delete%model%');


echo '</ul>';