<?php
/*
Plugin Name:  cinytamilsangam-org-ext-plugin
Plugin URI:   https://cincytamilsangam.org/ext/plugin
Description:  Custom code for Cincinnti Tamil Sangam Tamil School
Version:      1.0
*/
add_action('admin_menu', 'gcts_plugin_setup_menu');
 
 function gcts_plugin_setup_menu(){

        add_submenu_page('classdex_home','Unpaid Students',
        'Unpaid Students',
        'manage_options',
        '../unpaid-students/',
        'gcts_unpaid_students_init');         
 }
  
 function gcts_unpaid_students_init(){
     header("Location: https://cincytamilsangam.org/unpaid-students/"); /* Redirect browser */
        exit();
     
     
 }
?>
