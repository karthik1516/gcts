<?php
/*
Plugin Name:  cinytamilsangam-org-plugin
Plugin URI:   https://cincytamilsangam.org/plugin
Description:  Custom code for Cincinnati Tamil Sangam
Version:      1.9
*/

add_shortcode( 'my_membership_qr', 'build_my_membership_qr' );
function build_my_membership_qr( $atts ) {
    $uname=do_shortcode( '[currentuser_username]' );
    $url='https://cincytamilsangam.org/wp-admin/users.php?s='.$uname.'&action=-1';
    $html_out='<p>[dqr_code url="'.$url.'"]'.'</p>';    
  return $html_out;
}

add_shortcode( 'my_tickets_qr', 'build_my_tickets_qr' );
function build_my_tickets_qr( $atts ) {
    $uemail=do_shortcode( '[currentuser_useremail] ' );
    $url='https://cincytamilsangam.org/wp-admin/edit.php?s='.$uemail.'&post_status=all&post_type=mt-payments&action=-1';
    $html_out='<p>[dqr_code url="'.$url.'"]'.'</p>';    
  return $html_out;
}
