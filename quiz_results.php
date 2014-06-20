<?php
//Set the results of the quizzes here
//all results are numeric 'weights'
//winner is determined by whichever result the user picked has the highest total
//only the result weights are editable on this page
// ***KEEP IN MIND*** that if you change the name of the quiz - the shortcode that this page spits out to you will be reflected

global $wpdb;
if(!sizeof($_POST)) { //if you're coming on initial landing without choosing a quiz

  $quizzes = $wpdb->get_col("SELECT name FROM quizzes");
  $ids = $wpdb->get_col("SELECT id FROM quizzes");
?>
<div class="wrap">
    <h2>Quiz Results</h2>
    <form name="quiz_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php if(!$quizzes) { ?><h3>You have no quizes yet! Start your first one <a href="admin.php?page=new-quiz">here</a></h3><?php }else { ?>
          <input type="hidden" name="home" value="home" />
          <select name="quiz_id">
            <?php foreach($quizzes as $key => $quiz) {
              echo "<option name='{$quiz}' value='{$ids[$key]}'>{$quiz}</option>";
            } ?>
          </select>

          <p class="submit">
            <input type="submit" name="edit" value="Set Results" />
          </p>

        <?php } ?>

    </form>
</div>
<?php }else { //once you select a quiz to edit..
  $quizID = $_POST['quiz_id'];
  $quizName = $wpdb->get_var("SELECT name FROM quizzes WHERE id = {$quizID}");
  $outcomes = $wpdb->get_results("SELECT text, id FROM outcomes WHERE quiz_id = {$quizID}", ARRAY_A);

  if(isset($_POST['results'])) { //make sure the results were submitted
    //results require both an answer_id and outcome_id
    foreach($_POST['results'] as $key => $value) {
      foreach($value as $k => $r) {
        foreach($r as $kk => $result) {
          $wpdb->replace('results', array('weight' => $result, 'answer_id' => $key, 'outcome_id' => $k, 'id' => $kk)); //replace | same syntax as insert WITH update
        }
      }
    }
    echo "<div class='updated'>
      <p><strong>Results Saved</strong></p>
      <p>Copy paste this code to display the quiz: <code> [quizzes name='{$quizName}'] </code></p>
    </div>"; //outputs the shortcode that needs to be pasted into the post/page to actually display a quiz
    //based off of the quiz name, so if you change the name, you need to change the shortcode
    //example: [quizzes name='Are you a cat or dog person'] //**KEEP IN MIND** If the quiz name changes for whatever reason, this will change too
  }

?>

  <div class="wrap results">
  <h2>Quiz Results</h2>
  <form name="quiz_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  <input type="hidden" name="quiz_id" value="<?php echo $quizID; ?>" >

    <p id="quiz-name">Quiz Name: <?php echo $quizName; ?></p>

    <div class="question-holder">
      <?php
        foreach($wpdb->get_results("SELECT text, id FROM questions WHERE quiz_id = {$quizID}", ARRAY_A) as $qkey => $question) {
      ?>
        <div class="question">
          <p class="question-title">
            Quiz Question:
            <?php if($wpdb->get_var("SELECT is_img FROM questions WHERE id = {$question['id']}")) { ?>
                <img src="<?php echo $question['text']; ?>">
              <?php }else {
                echo $question['text'];
              } ?>
          </p>
          <div class="answer-holder">
          <?php foreach ($wpdb->get_results("SELECT text, id FROM answers WHERE question_id = {$question['id']}", ARRAY_A) as $akey => $answer) { ?>
            <div class="answer">
              Quiz Answer:
              <?php if($wpdb->get_var("SELECT is_img FROM answers WHERE id = {$answer['id']}")) { ?>
                <img height="200" src="<?php echo $answer['text']; ?>">
              <?php }else {
                echo "<strong>".$answer['text']."</strong>";
              } ?>
              <div class="outcome-holder">
                <h4>Weighted Values</h4>
                <?php
                if($wpdb->get_results("SELECT weight, id FROM results WHERE answer_id = {$answer['id']}", ARRAY_A)){

                  foreach($wpdb->get_results("SELECT weight, id FROM results WHERE answer_id = {$answer['id']}", ARRAY_A) as $key => $results) { ?>
                    <div class="outcome">
                      <strong><?php echo $outcomes[$key]['text']; ?></strong> value:
                      <input type="text" name="results[<?php echo $answer['id']; ?>][<?php echo $outcomes[$key]['id']; ?>][<?php echo $results['id']; ?>]" value="<?php echo $results['weight']; ?>" size="50" required>
                    </div>
                <?php } }else {
                  foreach($outcomes as $key => $value) {
                  ?>

                    <div class="outcome">
                      <strong><?php echo $outcomes[$key]['text']; ?></strong> value:
                      <input type="text" name="results[<?php echo $answer['id']; ?>][<?php echo $outcomes[$key]['id']; ?>][]" value="" size="50">
                    </div>
                <?php } } ?>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>
    <hr />

    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e('Update Options', 'quiz_trdom' ) ?>" />
    </p>

  </form>
</div>
<?php } ?>
