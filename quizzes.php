<?php
/**
 * Plugin Name: Quizzes
 * Description: Add a quiz menu
 * Version: 0.8.1
 */

/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'quiz_install');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'quiz_remove');

function quiz_install() { //create all the DB tables on activation!
  global $wpdb;
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //required to run
  $sql1 = "CREATE TABLE IF NOT EXISTS quizzes(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR(250) NOT NULL,
            UNIQUE KEY id (id)
          )";
  dbDelta($sql1);

  $sql2 = "CREATE TABLE IF NOT EXISTS questions(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            quiz_id mediumint(9) NOT NULL,
            text VARCHAR(250) NOT NULL,
            is_img tinyint(1) NOT NULL DEFAULT '0',
            caption, VARCHAR(250) DEFAULT NULL,
            UNIQUE KEY id (id)
          )";
  dbDelta($sql2);

  $sql3 = "CREATE TABLE IF NOT EXISTS answers(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question_id mediumint(9) NOT NULL,
            text VARCHAR(250) NOT NULL,
            is_img tinyint(1) NOT NULL DEFAULT '0',
            caption, VARCHAR(250) DEFAULT NULL,
            UNIQUE KEY id (id)
          )";
  dbDelta($sql3);

  $sql4 = "CREATE TABLE IF NOT EXISTS results(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            answer_id mediumint(9) NOT NULL,
            weight mediumint(9) NOT NULL,
            outcome_id mediumint(9) NOT NULL,
            UNIQUE KEY id (id)
          )";
  dbDelta($sql4);

  $sql5 = "CREATE TABLE IF NOT EXISTS outcomes(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            quiz_id mediumint(9) NOT NULL,
            text VARCHAR(250) NOT NULL,
            img VARCHAR(250) NOT NULL DEFAULT '',
            UNIQUE KEY id (id)
          )";
  dbDelta($sql5);

  $sql6 = "CREATE TABLE IF NOT EXISTS user_choices(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            quiz_id mediumint(9) NOT NULL,
            question_id mediumint(9) NOT NULL,
            answer_id mediumint(9) NOT NULL,
            the_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY id (id)
          )";
  dbDelta($sql6);
}

function quiz_remove() { //on deactivation.. remove all tables from DB? do nothing? ..?
}

add_action('admin_menu', 'quiz_plugin_menu');

function quiz_plugin_menu() { //all of the pages that come under the quiz tab in the admin back end
  add_menu_page('Quiz Options', 'Quizzes', 'manage_options', 'quizzes', 'quiz_home_page', '', 6);
  add_submenu_page('quizzes', 'New Quiz', 'New Quiz', 'administrator', 'new-quiz', 'quiz_new');
  add_submenu_page('quizzes', 'Edit Quiz', 'Edit Quiz', 'administrator', 'edit-quiz', 'quiz_edit');
  add_submenu_page('quizzes', 'Quiz Results', 'Quiz Results', 'administrator', 'quiz-results', 'quiz_results');
  add_submenu_page('quizzes', 'User Choices', 'User Choices', 'administrator', 'user-choices', 'quiz_user_choices');
}

function quiz_home_page(){
  include('quiz_home_page.php');
}
function quiz_new(){
  include('quiz_new.php');
}
function quiz_edit(){
  include('quiz_edit.php');
}
function quiz_results(){
  include('quiz_results.php');
}
function quiz_user_choices(){
  include('user_choices.php');
}

/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action('admin_enqueue_scripts', 'register_plugin_styles'); //css for admin backend
add_action('wp_enqueue_scripts', 'register_frontend_styles' ); //css for html output
add_action('admin_enqueue_scripts', 'register_plugin_scripts'); //js for admin backend
add_action('wp_enqueue_scripts', 'register_frontend_scripts'); //js for html output
add_shortcode('quizzes', 'register_shortcode');

/**
 * Enqueue plugin style-file
 */
function register_plugin_styles() {
    wp_register_style('quizzes', plugins_url('quizzes/css/style.css'));
    wp_enqueue_style('quizzes');
}
function register_frontend_styles() {
    wp_register_style('quizzes', plugins_url('quizzes/css/quizzes.css'));
    wp_enqueue_style('quizzes');
}
function register_plugin_scripts() {
    wp_register_script('quizzes', plugins_url('quizzes/js/scripts.js'));
    wp_enqueue_script('quizzes');
}
function register_frontend_scripts() {
    wp_register_script('quizzes', plugins_url('quizzes/js/quizzes.js'), array('jquery')); //requires jquery as a dependancy
    wp_enqueue_script('quizzes');
}

//setting up shortcode
// [quizzes foo="foo-value"]
function register_shortcode($name) {
    $a = shortcode_atts( array(
        'name' => '',
    ), $name );

    include('quiz_output.php'); //the page to output on shortcode usage
}

?>