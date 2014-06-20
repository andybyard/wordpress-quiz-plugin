//Back end admin JS file
jQuery(document).ready(function($) {

  var x = $('.question').length;

//add questions
  $(".add-question").click(function (e) {
    if(x <= 8) { // 10 max questions
      $(".question-holder").append('<div class="question" id="'+x+'" data-added="0"><span class="question-remove">x</span><p class="question-title"><label for="quiz_question_'+x+'">Question: </label><input type="text" id="quiz_question_'+x+'" name="new_questions[]" size="50" required></p></div>');
      x++;
    }
  return false;
  });

  var o = 0;
//add outcomes
  $(".add-outcome").click(function (e) {
    $(".outcome-holder").append('<div class="outcome"><label for="new_outcome_'+o+'">Outcome:</label> <input type="text" id="new_outcome_'+o+'" name="outcomes[]" value="" size="50" required><span class="outcome-remove">x</span></div>');
    o++;
  });

  var y = 0, w = 0;
//add answers
  $("body").on("click", ".add-answer", function (e) {
    y = $(this).parent().attr("id");
    if($(this).parent().data('qid')) {
      $(this).prev(".answer-holder").append('<p class="answer" data-added="0"><label for="quiz_answer_'+w+'">Answer: </label><input type="text" id="quiz_answer_'+w+'" name="new_answers['+$(this).parent().data('qid')+'][]" size="50" required><span class="answer-remove">x</span></p>');
      w++;
    }
  return false;
  });


//remove questions
  $("body").on("click", ".question-remove", function(e) {
    if(x > 1) {
      if(confirm('Are you sure you want to delete this question, and all answers tied to it?')) {
        if(!$(this).parent().data("added")) { //hasn't been added to the DB yet
          $(this).parent().remove();
        }else {
          jQuery.ajax({
            url: "../wp-content/plugins/quizzes/delete_from_db.php",
            type: "GET",
            data: {question: $(this).parent().data("qid")}
          });
          $(this).parent().remove();
        }
      }
      x--;
    }
  return false;
  });
//remove outcomes
  $("body").on("click", ".outcome-remove", function(e) {
      if(confirm('Are you sure you want to delete this outcome?')) {
        if(!$(this).parent().data("added")) { //hasn't been added to the DB yet
          $(this).parent().remove();
        }else {console.log($(this).parent().data("oid"));
          jQuery.ajax({
            url: "../wp-content/plugins/quizzes/delete_from_db.php",
            type: "GET",
            data: {outcome: $(this).parent().data("oid")}
          });
          $(this).parent().remove();
        }
      }
  });
//remove answers
  $("body").on("click", ".answer-remove", function(e) {
    if(confirm('Are you sure you want to delete this answer?')) {
      if(!$(this).parent().data("added")) {
        $(this).parent().remove();
      }else {
        jQuery.ajax({
          url: "../wp-content/plugins/quizzes/delete_from_db.php",
          type: "GET",
          data: {answer: $(this).parent().data("aid")}
        });
        $(this).parent().remove();
      }
    }
  });

});