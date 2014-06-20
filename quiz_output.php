<?php
  //This page is for the raw html that gets output when you use the shortcode
  //ie: what the user sees // front end
  //hidden fields are how the JS handles things

  $quizName = $a['name']; //$a is set in quizzes.php

  global $wpdb;
  $quizID = $wpdb->get_var("SELECT id FROM quizzes WHERE name = \"{$quizName}\""); //get said quiz id
  $outcomes = $wpdb->get_results("SELECT text, id, img FROM outcomes WHERE quiz_id = {$quizID}", ARRAY_A); //get all the outcomes from that quiz

?>

<div id='quiz-holder' data-quizID="<?php echo $quizID; ?>">
  <input type="hidden" id="outcomes" data-outcomes="<?php echo count($outcomes); ?>" <?php foreach ($outcomes as $key => $value) {echo " data-option".($key+1)."=\"".stripslashes($value['text'])."\"";} ?>>
  <input type="hidden" id="outcome_imgs" <?php foreach ($outcomes as $key => $value) {echo " data-option".($key+1)."=\"{$value['img']}\"";} ?>>
  <div id='quiz-name'><h3><? echo stripslashes($quizName); ?></h3></div>
    <div class="question-holder">
      <?php
        foreach($wpdb->get_results("SELECT text, id, caption FROM questions WHERE quiz_id = $quizID", ARRAY_A) as $qkey => $question) {
      ?>
        <div class="questions" id="q<?php echo $qkey; ?>">
          <div class="question">
            <?php if($wpdb->get_var("SELECT is_img FROM questions WHERE id = {$question['id']}")) { //if it's an image handle the output differently ?>
              <img src="<?php echo $question['text']; ?>">
              <div class="is-img-caption"><?php echo $question['caption']; ?></div>
            <?php }else {
              echo stripslashes($question['text']);
            } ?>
          </div>
          <div class="answer-holder">
              <?php foreach($wpdb->get_results("SELECT text, id, caption FROM answers WHERE question_id = {$question['id']}", ARRAY_A) as $akey => $answer) {
            ?>
            <div class="answer"
              <?php foreach($wpdb->get_results("SELECT weight, id FROM results WHERE answer_id = {$answer['id']}", ARRAY_A) as $key => $results) { ?>
                data-<?php echo "result{$key}"; ?>="<?php echo $results['weight']; ?>"
              <?php } ?> data-option="<?php echo $akey; ?>" data-answerID="<?php echo $answer['id']; ?>">

              <?php if($wpdb->get_var("SELECT is_img FROM answers WHERE id = {$answer['id']}")) { ?>
                <img src="<?php echo $answer['text']; ?>">
                <div class="is-img-caption"><?php echo $answer['caption']; ?></div>
              <?php }else {
                echo stripslashes($answer['text']);
              } ?>

            </div>
          <?php } ?>
          </div>
        </div>
      <?php } ?>
      <div id="the-answer"><span id="answer-id"></span></div>
    </div>
    <div id="quiz-fb">
    </div>
</div>