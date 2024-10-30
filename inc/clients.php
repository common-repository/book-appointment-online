<?php
 if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action('admin_footer','book_oz_clientTime',99,2);

function book_oz_persSpis_callback($persSpis) {
$args1 = array(
'post_type' => 'personal',
'post_status' => 'publish', //oz_re_timerange
'posts_per_page' => -1,
);
$posts = get_posts( $args1 );
if ($posts) {
	$persSpis = array();
	foreach( $posts as $post ) : setup_postdata( $post );
	$persSpis[] = $post->ID;
	wp_reset_postdata(); endforeach;
		}
	return $persSpis;
}
add_filter('book_oz_persSpis','book_oz_persSpis_callback',10,1);

function dataDayUsl_filter_callback($p,$id1) {
	$persId = array();
	if ($id1) :
	foreach ($id1 as $id) {
	switch (get_post_meta($id,'oz_book_provides_services',true)) {
		case 'all':
		$persId[] = $id;
		break;
		case 'include':
		$services = get_post_meta($id,'oz_re_timerange',true);
 		foreach( $services as $serv) {
			if ($serv['oz_personal_serv_name'] == $p) {
				$persId[] = $id; 
			}
		}
		break;
		case 'exclude':
		$services = get_post_meta($id,'oz_re_timerange',true);
 		foreach( $services as $serv) {
			if ($serv['oz_personal_serv_name'] != $p) {
				$persId[] = $id; 
			}
		}
		break;
		
	}
	}
	return implode(',',$persId);
	endif;
}

add_filter('dataDayUsl_filter','dataDayUsl_filter_callback',10,2);

function book_oz_clientTime($arg) {
	if (isset($_GET['post'])) {
$id = get_post_meta($_GET['post'],'oz_personal_field_id',true);

$arr = json_decode(get_post_meta($id,'oz_raspis',true),true);
if ($arr) {
$startC = array_column($arr, 'start');
$end = array_column($arr, 'end');
$start =  (isset($startС)) ? min($startС) : '';
$end =  max($end);
}
}
?>
<script>
jQuery(document).ready(function() {
var hourStart,hourFinish;
 <?php if (isset($start) && $start != '') : ?>
dayStart = <?php echo '"'.$start.'"'; ?> ;
hourStart = <?php echo explode(':',$start)[0]; ?> ;
<?php endif; ?>
<?php if (isset($end)) : ?>
dayFinish =  <?php echo '"'.$end.'"'; ?> ;
hourFinish = <?php echo explode(':',$end)[0]; ?> ;
<?php endif; ?>
});
</script>
<?php
}

add_action('book_oz_in_metabox','book_oz_in_metabox_clientTime',10,2);

function book_oz_in_metabox_clientTime($arg,$position) {
	if ($arg == 'book_oz_clientTime') {
		if ($position == 4) {
		global $post;
		$today = current_time('d.m.Y');
		$tr = '';
		$today = ($today) ? DateTime::createFromFormat('d.m.Y', $today) : '';
		$today = ($today) ? $today->format('U') : '';
		$dBooking = ($post) ? get_post_meta($post->ID,'oz_start_date_field_id', true) : '';
		$tBooking = ($post) ? get_post_meta($post->ID,'oz_time_rot', true) : '';
		$tBook = ($dBooking) ? DateTime::createFromFormat('d.m.Y', $dBooking) : '';
		$tBook = ($tBook) ? $tBook->format('U') : '';
		$timePass = '';
			if ($tBook && $today > $tBook) { 
			$timePass = '<div class="hidenextDiv">';
			$timePass .= sprintf(__('An appointment has already passed <b>%s</b> at <b>%s</b>','book-appointment-online'),date_i18n(get_option('date_format'),strtotime(get_post_meta( $post->ID, 'oz_start_date_field_id', true ))),date_i18n(get_option('time_format'),strtotime(get_post_meta( $post->ID, 'oz_time_rot', true ))));
			$timePass .= ' <span>'.__('Edit','book-appointment-online'). '</span></div>';
			$tr = ($timePass) ? 'hidenextTr' : '';
			} 
			echo '<tr class="'.$tr.'"><td class="at-field" colspan="2">'.$timePass.'</td></tr>';
			if (isset($_GET['post'])) {
			$id = get_post_meta($_GET['post'],'oz_personal_field_id',true);
			}
		}
	}
}