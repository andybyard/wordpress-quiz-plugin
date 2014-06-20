<?php //remove from DB when admin chooses to remove a question/answer/outcome from the backend
  require('../../../wp-load.php');
  global $wpdb;

  if(isset($_GET['question'])){
    $wpdb->delete('questions', array('id' => $_GET['question']));
    $wpdb->delete('answers', array('question_id' => $_GET['question']));
  }
  if(isset($_GET['answer'])){
    $wpdb->delete('answers', array('id' => $_GET['answer']));
  }
  if(isset($_GET['outcome'])){
    $wpdb->delete('outcomes', array('id' => $_GET['outcome']));
  }
?>