<?php
$s_authorErr = $s_author = "";

$quotes = motivation_quotes_get_all_quotes();

echo '<div class="research_quote">
		<h3>Research quotes by author</h3>
		<form action="" method="post">
			Author:<br>
			<input type="text" name="r_author" value="">
			<br>'.$s_authorErr.'<br>
  			<input type="submit" value="search">
		</form><br><br>
	</div>
	<div class="list_quotes">
	<table class="t_quotes">
		<tr>
			<th>Quote</th>
			<th>Author</th>
		</tr>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST['r_author'])) {
    $s_authorErr = "An author is required";
  } else {
    $s_author = motivation_quotes_test_input($_POST['r_author']);
    $quotes = motivation_quotes_get_quote_by_author($s_author);
  }
}

motivation_quotes_display_quotes($quotes);

echo '</table></div>';

function motivation_quotes_test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function motivation_quotes_get_all_quotes(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'motivational_quotes';

	$quotes = $wpdb->get_results("SELECT * FROM $table_name");
	return $quotes;

}

function motivation_quotes_get_quote_by_author($author){
	global $wpdb;
	$table_name = $wpdb->prefix . 'motivational_quotes';

	//$wpdb->show_errors( true );
	$q = $wpdb->get_results("SELECT * FROM $table_name WHERE author='$author'");
	return $q;
}

function motivation_quotes_display_quotes($list_quotes){
	$to_display = count($list_quotes);

	if($to_display > 50){
		$to_display = 50;
	}
	for($i = 0; $i < $to_display; $i++ ){
		echo '<tr class="row_quote"><td>'.$list_quotes[$i]->quote.'</td><td>'.$list_quotes[$i]->author.'</td></tr>';
	}
}

?>