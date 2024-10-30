<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if (!function_exists('book_oz_in_array_r')) {
function book_oz_in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && book_oz_in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

}
function book_oz_enqueue_datepicker() {
wp_enqueue_script( 'jquery-ui-datepicker' );
}
add_action( 'wp_enqueue_scripts', 'book_oz_enqueue_datepicker' );

add_action('wp_ajax_dayRaspis', 'book_oz_dayRaspis');
add_action('wp_ajax_nopriv_dayRaspis', 'book_oz_dayRaspis');


/* валидатор даты */
function book_oz_validateDate($date, $format = 'd.m.Y') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function book_oz_dayRaspis() {
	if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['ids'])) {
		$speci = (is_array(json_decode($_POST['ids']))) ? json_decode($_POST['ids']) : '';
		$dates = (isset($_POST['dateText']) && book_oz_validateDate($_POST['dateText'])) ? $_POST['dateText'] : '';
		$args = array(
		'post_type' => 'clients',
		'post_status' => 'publish',
 		'meta_query' => array(
			'relation' => 'AND',
			array(
			'key' => 'oz_start_date_field_id',
			'value' => $dates
			), 
 			array(
			'key' => 'oz_personal_field_id',
			'value' => $speci,
			'compare' => 'IN'
			), 
		) );
		$query = new WP_Query( $args );
		$clBr = array();
if ($query) {
	while ( $query->have_posts() ) : $query->the_post();
$timeStart = get_post_meta(get_the_id(),'oz_time_rot',true);
$usl =  get_post_meta(get_the_id(),'oz_uslug_set',true);
	$clBr[] = array(
			  'dayStart' => $dates,
			  'timeStart' => str_replace(' ','',$timeStart),
			  'pers_id' => get_post_meta(get_the_id(),'oz_personal_field_id',true),
			  'w_time' => get_post_meta($usl,'oz_serv_time',true)
			  );

	endwhile;
}
$clBr = json_encode($clBr);
echo $clBr;
		
		//echo print_r($query->the_post(),1);
	}
	wp_die();
	
}

add_action('wp_ajax_listUslug', 'book_oz_listUslug');
add_action('wp_ajax_nopriv_listUslug', 'book_oz_listUslug');

function book_oz_listUslug() {
	if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['id'])) {
		$start = microtime(true);
		$id = (int) ($_POST['id']);
		$ids = get_post_meta($id, 'oz_re_timerange',true);
		$idsP = [];
		if (!empty($ids)) {
			foreach ($ids as $idServ) {
				$idsP[] = $idServ['oz_personal_serv_name'];
			}
		}
		$exclude_serv = get_post_meta($id, 'oz_book_provides_services',true);
		$serv_in = ($exclude_serv == '' || $exclude_serv == 'include') ? array('post__in' => $idsP) : array('post__not_in' => $idsP) ; 
		$arg = array(
			'posts_per_page'   => -1,
			'post_type'        => 'services',
			'post_status'      => 'publish',
			'meta_query' => array(
				array(
				'key' => 'oz_serv_time',
				'value' => (int) ($_POST['minRange']),
				'type' => 'NUMERIC',
				'compare' => '<='
				), 
				),
			 );
		$args = array_merge_recursive($arg,$serv_in);
	$posts_array = new WP_Query( $args );
		if ($posts_array->have_posts()) {
		while ( $posts_array->have_posts() ) {
			$posts_array->the_post();
			$id = get_the_id();
			$price = get_post_meta($id,'oz_serv_price',true);
			?>
		<li id="usl-<?php echo $id; ?>" class="usluga">
		
			<p class="uslname"><?php echo get_the_title($id); ?></p>
			<div class="params_usl">
				<div class="oz_usl_time">
					<?php echo get_post_meta($id,'oz_serv_time',true); ?>
					<span class="oz_op"><?php _e('time (min)','book-appointment-online'); ?></span>
				</div>
				<?php if ($price) : ?>
				<div class="oz_usl_price">
					<?php echo $price; ?>
					<span class="oz_op"><?php _e('price','book-appointment-online'); ?> <?php if (get_option('oz_default_cur')) : echo '('.get_option('oz_default_cur').')'; endif; ?></span>
				</div>
				<?php endif; ?>
				<?php if (get_the_content($id)) : ?>
				<div class="oz_clear"></div>
				<div class="oz_serv_content">
					<?php echo get_the_content($id); ?>
				</div>
				<?php endif; ?>
			</div>
		</li>
			<?php
	}
	wp_reset_postdata();
	}
	
	else {
		_e('No services available. Try to change the time of the service or choose another specialist.','book-appointment-online');
	}
	}
	wp_die();
}



add_action('wp_ajax_do_zapis', 'book_oz_do_zapis');
add_action('wp_ajax_nopriv_do_zapis', 'book_oz_do_zapis');
function book_oz_do_zapis() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
		$persId = (isset($_POST['oz_personal_field_id'])) ? (int) ($_POST['oz_personal_field_id']) : '';
		$date = (book_oz_validateDate($_POST['oz_start_date_field_id'])) ? $_POST['oz_start_date_field_id'] : '';
		$time = (book_oz_validateDate($_POST['oz_time_rot'],'H:i')) ? $_POST['oz_time_rot'] : '';
		if (!$persId) {
		$args3 = array(
		'post_type' => 'personal',
		'post_status' => 'publish',
		'posts_per_page'   => -1,
		'post__in' => (is_array(json_decode($_POST['oz_filials']))) ? json_decode($_POST['oz_filials']) : '' ,
 		'meta_query' => array(
 			array(
			'key' => 'oz_clientsarray',
			'value' => $date.' '.$time,
			'compare' => 'NOT LIKE'
			), 
		) );
		$query3 = new WP_Query( $args3 );
		if ($query3) :
		while ( $query3->have_posts() ) : $query3->the_post();
		$persId =  get_the_id();
		endwhile;
		endif;
			//oz_clientsarray
		}
		$email = (isset($_POST['clientEmail'])) ? sanitize_email($_POST['clientEmail']) : '';
		$clientName = sanitize_text_field($_POST['clientName']);
		$date = (book_oz_validateDate($_POST['oz_start_date_field_id'])) ? $_POST['oz_start_date_field_id'] : '';
		$time = (book_oz_validateDate($_POST['oz_time_rot'],'H:i')) ? $_POST['oz_time_rot'] : '';
		$phone = (isset($_POST['clientPhone'])) ? sanitize_text_field($_POST['clientPhone']) : '';
		$remonsms = (isset($_POST['oz_remList']) && $_POST['oz_remList']) ? (int) ($_POST['oz_remList']) : 0; 
		$uslugi = (isset($_POST['oz_uslug_set']) && $_POST['oz_uslug_set']) ? sanitize_text_field($_POST['oz_uslug_set']) : ''; 
		$postarr = array(
		'post_title' => $clientName,
		'post_type' => 'clients',
		'post_status' => 'publish',
		'meta_input' => array(
		'oz_start_date_field_id' => $date,
		'oz_personal_field_id' =>$persId,
		'oz_time_rot' => $time,
		'oz_clientPhone' => $phone,
		'oz_clientEmail' => $email,
		'oz_remList' => $remonsms,
		'oz_uslug_set' => $uslugi
		
		)
		);
		$suc = wp_insert_post( $postarr, true );
		if ($suc) {
		$asr = array(array(
		'title' => sanitize_text_field($_POST['clientName']), //clientName
		'start' => $date.' '.$time,
		'w_time' => ($uslugi) ? get_post_meta($uslugi,'oz_serv_time',true) : '',
		));
		$der = json_decode(get_post_meta($persId ,'oz_clientsarray', true),true);
		$cer = array_merge($asr, $der);
		$cer = json_encode($cer,JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT);
		update_post_meta($persId,'oz_clientsarray', $cer);
		do_action('book_oz_send_ok',$suc);
		}
	}
	wp_die();
}

add_action('wp_ajax_checkusluga', 'book_oz_checkUsluga');
add_action('wp_ajax_nopriv_checkusluga', 'book_oz_checkUsluga');

function book_oz_checkUsluga() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
$args = array(
'post_type' => 'services',
'post_status' => 'publish',
'posts_per_page'   => -1,
);
$query = new WP_Query( $args );
if ($query) {
	$skolkoUslug = 0; // посчитаем услуги, если ноль, то выдадим ошибку
	while ( $query->have_posts() ) : $query->the_post();
	$id = get_the_id();
	$termsId = (isset($_POST['id'])) ? (int) ($_POST['id']) : '';
	$args1 = array();
$args1 = array(
'post_type' => 'personal',
'post_status' => 'publish', //oz_re_timerange
'meta_key' => 'oz_re_timerange',
'meta_value' => $id,
'meta_compare' => 'LIKE'
);

$tax_query = array(
			'tax_query' => array(
					array(
							'taxonomy' => 'filial',
							'field'    => 'term_id',
							'terms'    => $termsId,
							)
				),
			);
if (!isset($_POST['onlypers'])) {
$args1 = array_merge($args1,$tax_query);
}
$query1 = new WP_Query( $args1 );
if ($query1) {
	$newAr;
	$fe = array();
	$client_ID = array();
	while ( $query1->have_posts() ) : $query1->the_post();
	$id1 = get_the_id();
	/* исключаем услуги, которые специалист не оказывает или оказывает, только некоторые, тогда включаем их*/
	$incl = get_post_meta($id1,'oz_book_provides_services',true);
	if ($incl == 'exclude') {
		$mass = get_post_meta($id1,'oz_re_timerange',true);
		if (book_oz_in_array_r($id,$mass)) {
			continue;
		}
	}
	if ($incl == 'include') {
		$mass = get_post_meta($id1,'oz_re_timerange',true);
		if (!book_oz_in_array_r($id,$mass)) {
			continue;
		}
	}
	$chistAr = json_decode(get_post_meta($id1,'oz_raspis',true),true);
	$client_ID[] = $id1;
	$newAr[] = array_merge($newAr,$chistAr);
	foreach ($chistAr as $key => $chist) {
 	if (!book_oz_in_array_r($chist['day'],$fe) ) {
		if (!is_null($chist['day'])) {
		$fe[] = array(
		'day' => $chist['day'],
		'start' => $chist['start'],
		'end' => $chist['end'],
		'pId' => (isset($chist['pId'])) ? $chist['pId'] : ''
		);
		}
		
		else {
		$fe = array_merge(array($chistAr[$key]),$fe);
		}
	}

		else {
			$d = 0;
			while ($d <= count($fe) ) {
				if ( $chist['day'] === $fe[$d]['day']) {
					if (strtotime($chist['start']) < strtotime($fe[$d]['start']) ) {
					$de = array( $d => array( 
					'day' => $fe[$d]['day'],
					'start' => $chist['start'],
					'end' => $fe[$d]['end'],
					'pId' => (isset($fe[$d]['pId'])) ? $fe[$d]['pId'] : ''
					)
					);
					$fe = array_replace($fe,$de);
					}
					if (strtotime($chist['end']) > strtotime($fe[$d]['end']) ) {
					$de = array( $d => array( 
					'day' => $fe[$d]['day'],
					'start' => $fe[$d]['start'],
					'end' => $chist['end'],
					'pId' => (isset($fe[$d]['pId'])) ? $fe[$d]['pId'] : ''
					)
					);
					$fe =  array_replace($fe,$de);
					}
				}
				$d++;
			}
			
		}
			
		//$fe[] = array('day' => $chist['day']);
		$de = $chist['day'];
	}
		endwhile;
	}
	$query1->reset_postdata();
	if ($fe) :
		$timeUslug = get_post_meta($id,'oz_serv_time',true);
		$mintimeUslug = (!isset($mintimeUslug) || $timeUslug < $mintimeUslug) ? $timeUslug  : $mintimeUslug;
		$price = get_post_meta($id,'oz_serv_price',true);
	?>
	<?php //print_r($chistAr); ?>
	<li id="<?php echo $id; ?>" data-mintime="<?php echo $timeUslug; ?>" data-ids="<?php echo json_encode($client_ID); ?>" data-days='<?php echo json_encode($fe,JSON_UNESCAPED_SLASHES); ?>' data-raspis="" class="oz_service">
		<p><?php echo get_the_title($id); ?></p>
			<div class="params_usl">
				<div class="oz_usl_time">
					<?php  echo get_post_meta($id,'oz_serv_time',true); ?>
					<span class="oz_op"><?php _e('time (min)','book-appointment-online'); ?></span>
				</div>
				<?php if ($price) : ?>
				<div class="oz_usl_price">
					<?php echo $price; ?>
					<span class="oz_op"><?php _e('price','book-appointment-online'); ?> <?php if (get_option('oz_default_cur')) : echo '('.get_option('oz_default_cur').')'; endif; ?></span>
				</div>
				<?php endif; ?>
			<?php if (get_post_field('post_content', $id)) : ?>
			<div class="oz_serv_content">
				<?php echo get_post_field('post_content', $id); ?>
			</div>
			<?php endif; ?>
			</div>
	</li>
	<?php
	$skolkoUslug++;
	endif;
	endwhile;
	if (!$skolkoUslug) _e('Services not found! Choose employee','book-appointment-online-pro'); // добавил 08.04 вместо нижней строки
	if ($mintimeUslug) echo '<script>var mintimeUslug = '.$mintimeUslug.'; </script>';
	echo '<script>var mintimeUslug = '.$mintimeUslug.'; </script>';
	}
	else {
	_e('Error!','book-appointment-online-pro');
	}
wp_reset_query();
	}
	wp_die();
}

add_action('wp_ajax_checkpersonal', 'book_oz_checkPersonal');
add_action('wp_ajax_nopriv_checkpersonal', 'book_oz_checkPersonal');
function book_oz_checkPersonal() {
	if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['id'])) {
	$personal_ID = (int) ($_POST['id']);
$args = array(
'post_type' => 'personal',
'post_status' => 'publish',
'tax_query' => array(
    array(
    'taxonomy' => 'filial',
    'field' => 'id',
    'terms' => $personal_ID
     )
  )
);
$query = new WP_Query( $args );
if ($query) {
	?>
	<?php
while ( $query->have_posts() ) : $query->the_post();
$clAr = get_post_meta(get_the_id(),'oz_clientsarray',true);
$clAr = json_decode($clAr,true);
$clBr = array();
foreach ($clAr as $clA) {
	$w_time = (isset($clA['w_time'])) ? $clA['w_time'] : '';
	$clA1 = explode(' ',$clA['start']);
	$clA2 = explode(' ',$clA['start']);
	$clBr[] = array(
			  'dayStart' => $clA1[0],
			  'timeStart' => $clA2[1],
			  'w_time'	=> $w_time
			  );
}
$clBr = json_encode($clBr);
$days = json_decode(get_post_meta(get_the_id(),'oz_raspis',true),true);
	?>
		<?php 
		$allusl =  get_post_meta(get_the_id(), 'oz_re_timerange',true);
		$minpersTime = 9999;
			foreach ($allusl as $usl) {
			$id = $usl['oz_personal_serv_name'];
			$min = get_post_meta($id, 'oz_serv_time',true);
			if ($min < $minpersTime ) $minpersTime = $min;
			}	
		?>
			<li class="personal">
			<div class="oz_image">
				<?php $img = (get_the_post_thumbnail_url(get_the_id(),'thumbnail')) ? get_the_post_thumbnail_url(get_the_id(),'thumbnail') : esc_url(plugins_url( 'images/pers-ava.svg', dirname(__FILE__) )); ?>
					<div style="background-image:url('<?php echo $img; ?>')" class="oz_spec_back"></div>
				<?php if ( has_post_thumbnail() ) : the_post_thumbnail('oz-size'); else : ?><img src="<?php echo esc_url(plugins_url( 'images/pers-ava.svg', dirname(__FILE__) )); ?>" alt="personal image" /><?php endif; ?>
			</div>
			<div class="pers-content">
				<p data-mintime="<?php echo $minpersTime; ?>" data-usl='<?php echo json_encode(get_post_meta(get_the_id(), 'oz_re_timerange',true)); ?>' data-id="<?php echo get_the_id();  ?>" data-days='<?php echo get_post_meta(get_the_id(),'oz_raspis',true); ?>' data-raspis='<?php echo $clBr; ?>' class="pname"><?php the_title(); ?></p>
				<p class="special"><?php echo get_post_meta(get_the_id(), 'oz_specialnost',true); ?></p><br>
				<div class="oz_btn"><?php _e('Select', 'book-appointment-online'); ?></div>
				<?php global $oz_timezone; date_default_timezone_set($oz_timezone['chk']); ?>
			</div>
			</li>
	<?php 
endwhile;
?>
<?php
	}
wp_reset_query();
	}
	
	wp_die();
}	