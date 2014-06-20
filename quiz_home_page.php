<?php
//homepage of quizzes.
//same as edit page and results page on initial landing
//make this page something else?

  global $wpdb;
  $quizzes = $wpdb->get_col("SELECT name FROM quizzes");
  $ids = $wpdb->get_col("SELECT id FROM quizzes");
?>
<div class="wrap">
    <h2>Select Quiz</h2>
    <form name="quiz_form" method="post" action="admin.php?page=edit-quiz">
        <?php if(!$quizzes) { //makre sure we have quizzes ?><h3>You have no quizes yet! Start your first one <a href="admin.php?page=new-quiz">here</a></h3><?php }else { ?>
          <input type="hidden" name="home" value="home" />
          <select name="quiz_id">
            <?php foreach($quizzes as $key => $quiz) { //if we have quizzes..
              echo "<option name='".stripslashes($quiz)."' value='{$ids[$key]}'>".stripslashes($quiz)."</option>"; //create the dropdown of quizzes
            } ?>
          </select>

          <p class="submit">
            <input type="submit" name="edit" value="Select Quiz" />
          </p>

        <?php } ?>

    </form>
</div>