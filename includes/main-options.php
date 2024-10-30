<?php
$quote = $author = $quoteErr = $quoteErr2 = $authorErr = $del_quote = "";

echo '<div class="new_quote">
		<h3>Write a quote</h3>
		<form action="" method="post">
			Quote:<br>
			<input type="text" name="quote" value="">
			<br>'.$quoteErr.'<br>
			Author:<br>
	  		<input type="text" name="author" value="">
	  		<br>'.$authorErr.'<br>
	  		<input type="submit" value="Add quote">
		</form><br><br>
	</div>
	<div class="delete_quote">
		<h3>Quote to delete</h3>
		<form action="" method="post">
			Quote:<br>
			<input type="text" name="dquote" value="">
			<br>'.$quoteErr2.'<br>
	  		<input type="submit" value="Delete quote">
		</form><br><br>
	</div>';

include plugin_dir_path( __FILE__ ).'/liste.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["quote"])) {
    $quoteErr = "A quote is required";
  } else {
    $quote = motivation_quotes_test_input($_POST["quote"]);
  }
  
  if (empty($_POST["author"])) {
    $authorErr = "Author is required";
  } else {
    $author = motivation_quotes_test_input($_POST["author"]);
  }
  if (empty($_POST["dquote"])) {
    $quoteErr2 = "A quote is required";
  } else {
    $del_quote = motivation_quotes_test_input($_POST["dquote"]);
    motivation_quotes_delete_quote($del_quote);
  }
}

if($author != "" && $quote != ""){
	motivation_quotes_add_quote($quote, $author);
}


function motivation_quotes_add_quote($quote, $author){
	global $wpdb;
	$table_name = $wpdb->prefix . 'motivational_quotes';

	$wpdb->insert( 
		$table_name, 
		array( 
			'author' => $author, 
			'quote' => $quote, 
		) 
	);
}

function motivation_quotes_delete_quote($dquote){
	//faire fonctionner
	global $wpdb;
	$table_name = $wpdb->prefix . 'motivational_quotes';

	$wpdb->show_errors( true );
	$id = $wpdb->get_var("SELECT id FROM $table_name WHERE quote = '$dquote'");
	$wpdb->delete($table_name, array( 'id' => $id ));
}

?>