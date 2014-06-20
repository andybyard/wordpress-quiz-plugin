<?php
//page to edit quizzes
//also land on this page after a new quiz is created
//Can edit all quiz values here after a quiz is created (besides weighted results)
  //Where you add the answers and choose whether the items are images or not

global $wpdb;
if(sizeof($_POST)){ //make sure there is a quiz being viewed
  if(isset($_POST['quiz_new'])) { //IF coming from the new quiz page
    $quizName = $_POST['quiz_name'];

    $questions = $_POST['new_questions'];
    if(isset($_POST['new_answers'])){
      $answers = $_POST['new_answers'];
    }

    $wpdb->insert('quizzes', array("name" => $quizName)); //write quiz name to the db
    $quizID = $wpdb->get_var('SELECT LAST_INSERT_ID()'); //get the quiz id of the quiz

    $outcomes = $_POST['outcomes'];
    foreach($outcomes as $o => $outcome) {
      $wpdb->insert('outcomes', array('quiz_id' => $quizID, 'text' => $outcome)); //adding each outcome to db
    }

    $qid = []; //question ID
    foreach($questions as $question) {
      $wpdb->insert('questions', array('quiz_id' => $quizID, 'text' => $question)); //adding each question to db
      $qid[] = $wpdb->get_var('SELECT LAST_INSERT_ID()'); //getting that questions ID
    }

    echo '<div class="updated"><p><strong>Quiz Saved</strong></p></div>';
//if coming from the edit page
  }elseif(isset($_POST['edit'])) {
    $quizID = $_POST['quiz_id'];
    $quizName = $wpdb->get_var("SELECT name FROM quizzes WHERE id = $quizID");

    //not all values get changed every time
    if(isset($_POST['quiz_name'])) { //issets are required for each
      $wpdb->update("quizzes", array("name" => $_POST['quiz_name']), array("id" => $quizID)); //update quiz name
      $quizName = $wpdb->get_var("SELECT name FROM quizzes WHERE id = $quizID"); //reassign incase it has changed after update
    }
    if (isset($_POST['outcomes'])) {
      foreach($_POST['outcomes'] as $key => $value) {
        $wpdb->replace('outcomes', array('text' => $value['text'], 'img' => $value['img'], 'id' => $key, 'quiz_id' => $quizID)); //'replace' is same syntax as insert // but incorporates 'update' too
      }
    }
    if(isset($_POST['questions'])) {
      foreach($_POST['questions'] as $key => $value) {
        $wpdb->update('questions', array('text' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['q_captions'])) {
      foreach($_POST['q_captions'] as $key => $value) {
        $wpdb->update('questions', array('caption' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['qchk-bx'])) { //chk-bx's are optional flag, incase the provided is an image url
      foreach($_POST['qchk-bx'] as $key => $value) {
        $wpdb->update('questions', array('is_img' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['new_questions'])) { //new_questions are ones that get added after the fact
      foreach($_POST['new_questions'] as $key => $value) {
        $wpdb->insert('questions', array('quiz_id' => $quizID, 'text' => $value));
      }
    }
    if(isset($_POST['answers'])) {
      foreach($_POST['answers'] as $key => $value) {
        $wpdb->update('answers', array('text' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['a_captions'])) {
      foreach($_POST['a_captions'] as $key => $value) {
        $wpdb->update('answers', array('caption' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['achk-bx'])) {
      foreach($_POST['achk-bx'] as $key => $value) {
        $wpdb->update('answers', array('is_img' => $value), array('id' => $key));
      }
    }
    if(isset($_POST['new_answers'])) {
      foreach($_POST['new_answers'] as $qid => $answer) {
        foreach($answer as $key => $value) {
          $wpdb->insert('answers', array('question_id' => $qid, 'text' => $value));
        }
      }
    }

    if(!isset($_POST['home'])) { //to not display the updated string
      echo '<div class="updated"><p><strong>Quiz Updated</strong></p></div>';
    }

    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

  }
?>

<div class="wrap">
  <h2><?php echo stripslashes($quizName); ?></h2>

    <form id="quiz_form" name="quiz_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <input type="hidden" name="edit" value="Y">
      <input type="hidden" name="quiz_id" value="<?php echo $quizID; ?>">
      <p id="quiz-name"><label for="quiz_name">Quiz Name:</label><input type="text" id="quiz_name" name="quiz_name" value="<?php echo stripslashes($quizName); ?>" size="30"></p>
      <div class="outcome-holder">
        <?php foreach($wpdb->get_results("SELECT text, id, img FROM outcomes WHERE quiz_id = $quizID", ARRAY_A) as $o => $outcome) { ?>
          <div class="outcome" data-oid="<?php echo $outcome['id']; ?>" data-added="1">
            <span class="outcome-remove">x</span>
            <label for="outcome_<?php echo $o; ?>">Outcome:</label> <input type="text" id="outcome_<?php echo $o; ?>" name="outcomes[<?php echo $outcome['id'] ?>][text]" value="<?php echo stripslashes($outcome['text']); ?>" size="50"><br>
            <label for="outcome_img_<?php echo $o; ?>">Image:</label> <input type="text" id="outcome_img_<?php echo $o; ?>" name="outcomes[<?php echo $outcome['id'] ?>][img]" value="<?php echo stripslashes($outcome['img']); ?>" size="50">
          </div>
        <?php } ?>
      </div>
      <div class="add-outcome">+ Add Outcome</div>
      <div class="question-holder">
        <?php foreach($wpdb->get_results("SELECT text, id, caption FROM questions WHERE quiz_id = $quizID", ARRAY_A) as $qkey => $question) { ?>
          <div class="question" data-qid="<?php echo $question['id']; ?>" data-added="1">
            <div class="question-remove">x</div>
            <p class="question-title">
              <label for="question_<?php echo $qkey; ?>">Question: </label>
              <input type="text" id="question_<?php echo $qkey; ?>" name="questions[<?php echo $question['id']; ?>]" value="<?php echo stripslashes($question['text']); ?>" size="50">
              <br><input type="hidden" name="qchk-bx[<?php echo $question['id']; ?>]" value="0" >
              <input type="checkbox" id="qchk_<?php echo $qkey; ?>" name="qchk-bx[<?php echo $question['id']; ?>]" value="1" <?php if($wpdb->get_var("SELECT is_img FROM questions WHERE id = {$question['id']}")){echo "checked";} ?>><label for="qchk_<?php echo $qkey; ?>">Is this an image?</label>
              <br><label for="q_caption_<?php echo $qkey; ?>">Image Caption:</label><input type="text" id="q_caption_<?php echo $qkey; ?>" name="q_captions[<?php echo $question['id']; ?>]" value="<?php echo stripslashes($question['caption']); ?>" size="50">
            </p>
            <div class="answer-holder"><br>
            <?php foreach($wpdb->get_results("SELECT text, id, caption FROM answers WHERE question_id = {$question['id']}", ARRAY_A) as $akey => $answer) { ?>
              <p class="answer" data-aid="<?php echo $answer['id']; ?>" data-added="1">
                <label for="answer_<?php echo $qkey; ?>_<?php echo $akey; ?>">Answer: </label>
                <input type="text" id="answer_<?php echo $qkey; ?>_<?php echo $akey; ?>" name="answers[<?php echo $answer['id']; ?>]" value="<?php echo stripslashes($answer['text']); ?>" size="50">
                <span class="answer-remove">x</span>
                <br><input type="hidden" name="achk-bx[<?php echo $answer['id']; ?>]" value="0" >
                <input type="checkbox" id="qchk_<?php echo $qkey; ?>_<?php echo $akey; ?>" name="achk-bx[<?php echo $answer['id']; ?>]" value="1" <?php if($wpdb->get_var("SELECT is_img FROM answers WHERE id = {$answer['id']}")){echo "checked";} ?>><label for="qchk_<?php echo $qkey; ?>_<?php echo $akey; ?>">Is this an image?</label>
                <br><label for="a_caption_<?php echo $qkey; ?>_<?php echo $akey; ?>">Image Caption:</label><input type="text" id="a_caption_<?php echo $qkey; ?>_<?php echo $akey; ?>" name="a_captions[<?php echo $answer['id']; ?>]" value="<?php echo stripslashes($answer['caption']); ?>" size="50">
              </p>
            <?php } ?>
            </div>
            <div class="add-answer">+ Add Answer</div>
          </div>
        <?php } ?>
      </div>
      <div class="add-question">+ Add Question</div>
      <hr />

      <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options', 'quiz_trdom' ) ?>" />
      </p>
    </form>
</div>
<?php }else { //when landing on the edit page initially
              //create a dropdown box to be able to select the quiz
  $quizzes = $wpdb->get_col("SELECT name FROM quizzes");
  $ids = $wpdb->get_col("SELECT id FROM quizzes");
?>
<div class="wrap">
  <h2>Select Quiz</h2>

  <form name="quiz_form" method="post" action="admin.php?page=edit-quiz">
    <?php if(!$quizzes) { //make sure there are actually quizzes ?>
      <h3>You have no quizes yet! Start your first one <a href="admin.php?page=new-quiz">here</a></h3>
    <?php }else { //if we got quizzes.. ?>
      <input type="hidden" name="home" value="home" />
      <select name="quiz_id">
        <?php foreach($quizzes as $key => $quiz) { //make dropdown of quizzes
          echo "<option name='".stripslashes($quiz)."' value='{$ids[$key]}'>".stripslashes($quiz)."</option>";
        } ?>
      </select>

      <p class="submit">
        <input type="submit" name="edit" value="Select Quiz" />
      </p>
    <?php } ?>
  </form>
</div>
<?php } ?>