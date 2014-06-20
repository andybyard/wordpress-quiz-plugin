//front end(html) js file
var answerids = [];
var outcomes = [];

function doAnswer() {
  answerids.push(jQuery(this).data("answerid")); //add the answer IDS into array

  var num = jQuery('#outcomes').data('outcomes'); //number of outcomes in the quiz
  for(var i=0;i<num;i++) {
    if(outcomes.length < num) {
      outcomes.push(jQuery(this).data("result"+i));
    }else {
      outcomes[i] += jQuery(this).data("result"+i);
    }
  }

  jQuery(this).parent().parent().css('display', 'none'); //hide this question
  jQuery(this).parent().parent().next().css('display', 'block'); //display next question

  if(jQuery(this).parent().parent().next().is('#the-answer')) {

    var winner = Math.max.apply( Math, outcomes); //which one won

    jQuery.each(outcomes, function(index, value) {
      if(value === winner){ jQuery('#answer-id').append('<img id="post-img" src="'+jQuery('#outcome_imgs').data('option'+(index+1)) + '"><div id="post-text">' + jQuery('#outcomes').data('option'+(index+1)) + "</div>"); }
    });

    jQuery('#quiz-fb').append('<button id="shareonfacebook">Share your results on Facebook!</button>');

    jQuery.each(answerids, function(index, value) {
      jQuery.ajax('../../../wp-content/plugins/quizzes/quiz_record.php?quiz_id=' + jQuery("#quiz-holder").data("quizid") + '&answer_id=' + value);
    });
  }
}

jQuery(document).ready(function($) {
  $('.questions .answer').on('click', doAnswer);
  jQuery('body').on('click', '#shareonfacebook', function (e) {
    console.log(document.URL);
    e.preventDefault();
    FB.ui(
    {
      method: 'feed',
      name: jQuery('.page-title').html(),
      link: document.URL,
      description: 'I got ' + jQuery('#post-text').html() + ' How about you?',
      picture: jQuery('#post-img').attr('src')
     });
   });
});

