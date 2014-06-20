<?php //called when the user finishes a quiz to record choices
require( '../../../wp-load.php' );
global $wpdb;
if(isset($_GET['answer_id'])){
  $answer_id = $_GET["answer_id"];
  $question_id = $wpdb->get_var("SELECT question_id FROM answers WHERE id = {$answer_id}");

  $wpdb->query("INSERT INTO user_choices (quiz_id, question_id, answer_id) VALUES ('".$_GET['quiz_id']."', '".$question_id."', '".$answer_id."')");

  }

?>