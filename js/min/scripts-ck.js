jQuery(document).ready(function(e){e("#results-num").each(function(){e(this).data("lastValue",e(this).val())}),e("#results-num").change(function(a){var t=e(this).data("lastValue"),s=e(this).val();confirm("Are you sure you want to change the possible results from "+t+" to "+s+"?")?(e(this).data("lastValue",s),e(".result-holder").append(e(this))):e(this).val(t)});var a=e(".question").length;e(".add-question").click(function(t){return 8>=a&&(e(".question-holder").append('<div class="question" id="'+a+'" data-added="0"><span class="question-remove">x</span><p class="question-title"><label for="quiz_question">Quiz Question: </label><input type="text" name="new_questions[]" size="50"></p><div class="answer-holder"><p class="answer"><label for="quiz_answer">Quiz Answer: </label><input type="text" name="answers['+a+'][]" size="50"><span class="answer-remove">x</span></p></div><div class="add-answer">+ Add Answer</div></div>'),a++),!1});var t=0;e("body").on("click",".add-answer",function(a){return t=e(this).parent().attr("id"),e(this).prev(".answer-holder").append('<p class="answer" data-added="0"><label for="quiz_answer">Quiz Answer: </label><input type="text" name="answers['+t+'][]" size="50"><span class="answer-remove">x</span></p>'),!1}),e("body").on("click",".question-remove",function(t){return a>1&&(confirm("Are you sure you want to delete this question, and all answers tied to it?")&&(e(this).parent().data("added")?(jQuery.ajax({url:"../wp-content/plugins/quizzes/delete_from_db.php",type:"GET",data:{question:e(this).parent().data("qid")}}),e(this).parent().remove()):e(this).parent().remove()),a--),!1}),e("body").on("click",".answer-remove",function(a){confirm("Are you sure you want to delete this answer?")&&(e(this).parent().data("added")?(jQuery.ajax({url:"../wp-content/plugins/quizzes/delete_from_db.php",type:"GET",data:{answer:e(this).parent().data("aid")}}),e(this).parent().remove()):e(this).parent().remove())})});