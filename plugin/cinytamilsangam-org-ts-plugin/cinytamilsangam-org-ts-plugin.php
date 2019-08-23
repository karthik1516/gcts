<?php
/*
Plugin Name:  cinytamilsangam-org-ts-plugin
Plugin URI:   https://cincytamilsangam.org/ts/plugin
Description:  Custom code for Cincinnti Tamil Sangam Tamil School
Version:      1.1
*/
class Ts_students {
	/**
	 * Constructor
	 * @return void
	 */
   public function __construct() {
    add_shortcode( 'ts_students', array( $this, 'build_ts_students' ) );
    add_shortcode( 'ts_fees_paid', array( $this, 'set_fees_paid' ) );
   }

   public function set_fees_paid( $atts ) {

    global $wpdb;

    $uemail=do_shortcode( '[currentuser_useremail] ' );
    $students=$this->get_students($uemail);
    foreach ($students as $student) {
      
      $wpdb->update('wpav_classdex_registrations',
         array('paid'=>1),
         array('cust_id' => $student->cust_id)
      );
    }
    return "<span></span>";
   }

   private function get_students($uemail){
    global $wpdb;
    $q="SELECT cust_id, first_name, last_name, email, notes FROM wpav_classdex_customers WHERE email='".$uemail."'";
    return $wpdb->get_results($q);
   }
   private function get_registered_classes($cust_id){
    global $wpdb;
    $q="SELECT t1.class_id, title, teacher, paid, fee FROM wpav_classdex_registrations t1, wpav_classdex_classes t2 WHERE t1.cust_id=".$cust_id." and t1.class_id=t2.class_id" ;
    return $wpdb->get_results($q);
   }
   private function build_reg_class($reg_class){
     $o='<tr data-classid="'.$reg_class->class_id.'">';
     $o=$o.'<td>'.$reg_class->title.'</td>';
     $o=$o.'<td>'.$reg_class->teacher.'</td>';
     if ($reg_class->paid==1){
      $o=$o.'<td><img  style="max-height:40px" alt="Paid" src="'.plugin_dir_url( __FILE__ ) .'img/paid.jpg" border="0"/></td>';
      
     }else{
      $o=$o.'<td>Not Paid</td>';
     }
     $o=$o.'<td>$'.$reg_class->fee.'</td>';
     $o=$o."</tr>";
     return $o;
    }
    private function compute_total_fees($students){

      $fees=0.0;
      foreach ($students as $student) {
        $reg_classes=$this->get_registered_classes($student->cust_id);
        foreach ($reg_classes as $reg_class) {
          if ($reg_class->paid==0){
            $fees=$fees+((float)$reg_class->fee);
          }
        }
      }
      return $fees;
    }
  
   
   
   private function build_student($student){
    $o='<div class="ts_student border rounded" data-custid="'.$student->cust_id.'">';
    $o=$o.'<div class="font-weight-bold">'.$student->first_name.' '.$student->last_name.'</div>';
    $o=$o.'<p>Notes: '.$student->notes.'</p>';
    $reg_classes=$this->get_registered_classes($student->cust_id);

    $ct=0;
    $o2="<table>";

    foreach ($reg_classes as $reg_class) {
      $o2=$o2.$this->build_reg_class($reg_class);
      $ct=$ct+1;
    }
    $o2=$o2."</table>";
    if ($ct==0){
      $o=$o.'<div class="font-weight-bold">Not Registered.<button  class="btn btn-primary btn-sm register-btn">Register No Books 90$</button><button  class="btn btn-primary btn-sm register-no-books-btn">Register with Books 125$</button></div>';
    }else{
      $o=$o.$o2;
    }
    $o=$o.'</div>';
    return $o;
   }
   private function build_add_student($uemail){
     $o='<div id="add-student-div" class="mt-3" data-uemail="'.$uemail.'">';
     $o=$o.'<button id="add-student-btn" class="btn btn-primary mx-3" style="display:none">Add Student</button>';

     $o=$o.'<button id="make-payment-btn" class="btn btn-primary mx-3 invisible">Make Payment</button>';
     
     $o=$o.'</div>';
     return $o;

   }
   public function build_ts_students( $atts ) {
    wp_enqueue_style( 'ts_students', plugin_dir_url( __FILE__ ) . 'css/style.css' );
    wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
    

    
    wp_enqueue_script( 'ts_students', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery' ), null, true );


    wp_enqueue_script( 'popper',  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'bootstrap-js',  'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'paypal',  'https://www.paypalobjects.com/api/checkout.js', array( 'jquery' ), null, true );
    
     $uemail=do_shortcode( '[currentuser_useremail] ' );
     $students=$this->get_students($uemail);

    

     $o='<div class="ts_students container-fluid">';
     $a = shortcode_atts( array(
      'env' => 'sandbox'
    ), $atts );

    $o=$o.'<span id="env" data-val="'.$a['env'].'"></span>';
         
     $ct=0;
     foreach ($students as $student) {
       $o=$o.$this->build_student($student);
       $ct=$ct+1;
     }
     if ($ct==0){
       $o=$o.'<p>No students in your account. Next Step: Tap on Add Student button.</p>';
     }
     $o=$o.$this->build_add_student($uemail);

     $fees=$this->compute_total_fees($students);

     if ($fees>0){
      $o=$o.'<p>Total Fees Due: '.strval($fees).'</p>'; 
      $o=$o.'<div id="paypal-button" data-total="'.strval($fees).'"><div>';
     }else{
      $o=$o.'<p>Paid in full. Thank you.</p>'; 
      $o=$o.'<div id="paypal-button" style="display:none" data-total="0.0"><div>';
     }
    
     $o=$o.'</div>';
     return $o;
   }
    
}

new Ts_students();

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
