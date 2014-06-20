<?php
  //page for creating a new quiz
  //on submit sends to the edit page
  //Quiz name, questions, and outcomes are added here. Answers on the edit page (due to needing the question_id to add an answer)
?>
<div class="wrap">
  <h2>New Quiz</h2>

    <form name="quiz_form" method="post" action="admin.php?page=edit-quiz">
        <input type="hidden" name="quiz_new" value="Y">
        <p id="quiz-name"><label for="quiz_name">Quiz Name:</label> <input type="text" id="quiz_name" name="quiz_name" size="30" required></p>
        <div class="outcome-holder">
          <div class="outcome">
            <label for="outcome_<?php echo $o; ?>">Outcome:</label><input type="text" id="outcome_<?php echo $o; ?>" name="outcomes[]" value="" size="50" required>
            <span class="outcome-remove">x</span>
          </div>
        </div>
        <div class="add-outcome">+ Add Outcome</div>
        <div class="question-holder">
          <div class="question" id="0">
            <p class="question-title"><label for="question_1">Quiz Question: </label><input type="text" id="question_1" name="new_questions[]" size="50" required></p>
          </div>
        </div>
        <div class="add-question">+ Add Question</div>
        <hr />

        <p class="submit">
        <input type="submit" name="Submit" value="Update Options" />
        </p>
    </form>
</div>