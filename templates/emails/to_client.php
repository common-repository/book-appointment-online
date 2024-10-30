<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
send email to client

$idKlienta = post id clienta
$komy = client
*/


$title_spec = get_the_title(get_post_meta($idKlienta,'oz_personal_field_id',true));
$title_usl = get_the_title(get_post_meta($idKlienta,'oz_uslug_set',true));
$datas = array(
__('Date', 'book-appointment-online') => array(
	'value' => date_i18n(get_option('date_format'),strtotime(get_post_meta( $idKlienta, 'oz_start_date_field_id', true )))
	),
__('Time booking', 'book-appointment-online') => array(
	'value' => date_i18n(get_option('time_format'),strtotime(get_post_meta( $idKlienta, 'oz_time_rot', true )))
	),
__('Service', 'book-appointment-online') => array(
	'value' => $title_usl,
	),
__('Specialist', 'book-appointment-online') => array(
	'value' => $title_spec ,
	)
);
 ?>
 <html>
	<body>
		<p><?php _e('Thank you!', 'book-appointment-online'); ?></p>
		<p><?php echo get_the_title($idKlienta); ?>, <?php _e('Your data:', 'book-appointment-online'); ?></p>
		<?php if ($datas) : ?>
			<ul>
			<?php foreach($datas as $key => $data) :
			?>
			<?php if ($data['value']) : ?>
				<li><b><?php echo $key; ?>:</b> <?php echo $data['value']; ?></li>
			<?php endif; ?>
			<?php endforeach; ?>
			</ul>
	</body>
 </html>
<?php endif; ?>