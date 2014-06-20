<?php
//This page is sort of a real rough and crude dream page for down the line
//somehow show what users picked when they did the quiz and display them back?
//incorporate GA somehow?
//Right now it creates a table that shows the quiz, the question that they answered, which answer they picked, and a timestamp when they did it.
//This table (and DB table..) will get real bulky real fast. //might not be a good idea, might not want/need to include this page?

global $wpdb;
$user_data = $wpdb->get_results("SELECT quiz_id, question_id, answer_id, the_time FROM user_choices"); //don't need id from *

// $user_data = $wpdb->get_results(
// " SELECT quizzes.id AS quiz_id,
//          outcomes.id AS outcome_id,
//          questions.id AS question_id,
//          answers.id AS answer_id,
//          results.id AS results_id
//   FROM quizzes
//     LEFT JOIN outcomes
//       ON quizzes.id = outcomes.quiz_id
//     LEFT JOIN questions
//       ON quizzes.id = questions.quiz_id
//     LEFT JOIN answers
//       ON questions.id = answers.question_id
//     LEFT JOIN results
//       ON answers.id = answer_id
//   WHERE quizzes.id = 138
// ", ARRAY_A);

// echo "<pre>";
// var_dump($user_data);
// echo "</pre>";

?>

<div class="wrap">
  <h2><?php echo "User Choices"; ?></h2>

  <table id="quiz-results">
    <tr>
      <th>Quiz</th>
      <th>Question</th>
      <th>Answer</th>
      <th>Timestamp</th>
    </tr>
<?php
  foreach($user_data as $key => $value) {
    get_object_vars($value);
    echo "<tr>";
    foreach($value as $key2 => $value2) {
?>
      <td>
        <?php if($key2 === 'quiz_id') { echo $wpdb->get_var("SELECT name FROM quizzes WHERE id = {$value2}"); }
        elseif($key2 === 'question_id') { echo $wpdb->get_var("SELECT text FROM questions WHERE id = {$value2}"); }
        elseif($key2 === 'answer_id') { echo $wpdb->get_var("SELECT text FROM answers WHERE id = {$value2}"); }
        elseif($key2 === 'the_time') { echo $value2; }
         ?>
      </td>
<?php
    }
      echo "</tr>";
  } ?>
  </table>

</div>