<?php
if ( ! defined( 'ABSPATH' ) ) wp_die();
add_action('init','register_book_oz_metabox');
function register_book_oz_metabox() {
if (is_admin()){
	$prefix = 'oz_';
	
	
	$config = array(
    'id'             => 'client_meta_box',          // meta box id, unique per meta box
    'title'          => __('Client data','book-appointment-online'),          // meta box title
    'pages'          => array('clients'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false,          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	'callback'		=> 'book_oz_clientTime'
  );
  
	$clients =  new AT_Meta_Box($config); 
	$clients->addText($prefix.'clientPhone',array('name'=> __('Phone','book-appointment-online')));
	$clients->addText($prefix.'clientEmail',array('name'=> __('Email','book-appointment-online')));
	$clients->addPosts($prefix.'uslug_set',array('post_type' => 'services', 'args' => array('order' => 'ASC','orderby' => 'title')),array('name'=> __('Services','book-appointment-online'),'emptylabel' => __('Not select','book-appointment-online'),'style' => 'width:100%;'));
	$clients->addPosts($prefix.'personal_field_id',array('post_type' => 'personal', 'args' => apply_filters('book_oz_get_posts', array()) ),array('name'=> __('Employe name','book-appointment-online'),'inGroup' => true, 'emptylabel' => __('Not select','book-appointment-online'),'style' => 'width:100%;'));
	$clients->addDate($prefix.'start_date_field_id',array('name'=> __('Date','book-appointment-online'),'inGroup' => true, 'asDiv' => true)); //'format'=>'yy.mm.dd' - такой формат работает с between
	$clients->addText($prefix.'time_rot',array('name'=> __('Booking time','book-appointment-online'),'inGroup' => true));	 // поменять потом на addHidden
	$clients->Finish();
	
	$configp1 = array(
    'id'             => 'book_oz_personal1_meta_box',
    'title'          => __('About employe','book-appointment-online'),
    'pages'          => array('personal'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),         
    'local_images'   => false,
    'use_with_theme' => false,
	'callback'		 => ''
  );
  
	$personal1 =  new AT_Meta_Box($configp1);
	$personal1->addText($prefix.'specialnost',array('name'=> __('Specialty','book-appointment-online')));
	$personal1->Finish();
	
	$config1 = array(
    'id'             => 'book_oz_personal_meta_box',
    'title'          => __('Schedule','book-appointment-online'),
    'pages'          => array('personal'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),         
    'local_images'   => false,
    'use_with_theme' => false,
	'callback'		 => 'book_oz_worktime'
  );
  
	$personal =  new AT_Meta_Box($config1);
	$personal->addHidden($prefix.'raspis',array('name'=> __('Schedule','book-appointment-online')));
	$personal->addHidden($prefix.'clientsarray',array('name'=> __('Clients','book-appointment-online')));	
	$timeRange[] = $personal->addPosts($prefix.'personal_serv_name',array('post_type' => 'services'),array('name'=> __('Service name','book-appointment-online')),true);
	//$timeRange[] = $personal->addSelect($prefix.'select_time_serv',array('5'=>'5','10'=>'10','15'=>'15','20'=>'20','25'=>'25','30'=>'30','35'=>'35','40'=>'40','45'=>'45','50'=>'50','55'=>'55','60'=>'60'),array('name'=> 'Промежуток', 'std'=> array('55')),true);
	$personal->addRepeaterBlock($prefix.'re_timerange',array(
    'inline'   => true, 
    'name'     => __('Services list','book-appointment-online'),
    'fields'   => $timeRange, 
    'sortable' => true
  ));
	$personal->Finish();
	
	$cConfig = array(
    'id'             => 'book_oz_calendar_n',
    'title'          => __('Booking calendar','book-appointment-online'),
    'pages'          => array('personal'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),         
    'local_images'   => false,
    'use_with_theme' => false,
	'callback'		 => 'book_oz_calendarSot'
  );
  
 	$personalC =  new AT_Meta_Box($cConfig);
	$personalC->addHidden($prefix.'start_date_field',array('name'=> __('Date','book-appointment-online')));
	$personalC->Finish(); 
	
	$servConf = array(
    'id'             => 'book_oz_service',
    'title'          => __('Service params','book-appointment-online'),
    'pages'          => array('services'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),         
    'local_images'   => false,
    'use_with_theme' => false,
	'callback'		 => ''
  );
  
  $time = array();
  $dur = 15;
  for ($i=0; $i <= 12;$i++) {
	  $time[$i*$dur] = $i*$dur;
  }
 	$personalC =  new AT_Meta_Box($servConf);
	$personalC->addText($prefix.'serv_price',array('name'=> __('Price','book-appointment-online')));
	$personalC->addSelect($prefix.'serv_time',$time, array('name'=> __('Service time','book-appointment-online')));
	$personalC->Finish(); 
}
}
?>