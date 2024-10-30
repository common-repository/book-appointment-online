<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
// Front shortcode template

ob_start();
$tolko_usl = true;
?>
<div class="oz_container">
	<div class="text-center">
	<div class="oz_back_btn fadeOutTop"><?php _e('Back', 'book-appointment-online'); ?></div>
	<?php do_action('book_oz_before_step_title'); ?>
		<h3 class="stepname"><?php _e('Select employee', 'book-appointment-online'); ?></h3>
	</div>
<div class="oz_hid">
	<div class="oz_hid_carousel clearfix">
	<?php
	$args = array( 
	'posts_per_page' => -1, 
	'post_type' => 'personal' 
	);
	$personals = get_posts( $args );
	?>
	<?php if ($personals) : ?>
	<script> var persStart = true;</script>
	<ul class="personals">
	<?php
	$tolko_usl = false;
	foreach ( $personals as $personal ) : setup_postdata( $personal );
$id = $personal->ID;
$clAr = array();
$clAr = get_post_meta($id,'oz_clientsarray',true);
$clAr = json_decode($clAr,true);
$clBr = array();
if ($clAr) {
foreach ($clAr as $clA) {
	$w_time = (isset($clA['w_time'])) ? $clA['w_time'] : '';
	$clBr[] = array(
			  'dayStart' => explode(' ',$clA['start'])[0],
			  'timeStart' => explode(' ',$clA['start'])[1],
			  'w_time'	=> $w_time
			  )
			  ;
}
}

$clBr = json_encode($clBr);
$days = json_decode(get_post_meta($id,'oz_raspis',true),true);
		?>
		<?php 
		$allusl =  get_post_meta($id, 'oz_re_timerange',true);
		$minpersTime = 9999;
		if ($allusl) {
			foreach ($allusl as $usl) {
			$id_1 = $usl['oz_personal_serv_name'];
			$min = get_post_meta($id_1, 'oz_serv_time',true);
			if ($min < $minpersTime ) $minpersTime = $min;
			}	
		}
		?>
			<li class="personal">
				<div class="oz_image">
					<?php $img = (get_the_post_thumbnail_url($id,'thumbnail')) ? get_the_post_thumbnail_url($id,'thumbnail') : esc_url(plugins_url( 'images/pers-ava.svg', dirname(__FILE__) )); ?>
					<div style="background-image:url('<?php echo $img; ?>')" class="oz_spec_back"></div>
					<?php if ( has_post_thumbnail($id) ) : echo get_the_post_thumbnail($id,'thumbnail'); else : ?><img src="<?php echo esc_url(plugins_url( 'images/pers-ava.svg', dirname(__FILE__) )); ?>" alt="personal image" /><?php endif; ?>
				</div>
				<div class="pers-content">
					<p data-mintime="<?php echo $minpersTime; ?>" data-usl='<?php echo json_encode(get_post_meta($id, 'oz_re_timerange',true)); ?>' data-id="<?php echo $id;  ?>" data-days='<?php echo get_post_meta($id,'oz_raspis',true); ?>' data-raspis='<?php echo $clBr; ?>' class="pname"><?php echo get_the_title($id); ?></p>
					<p class="special"><?php echo get_post_meta($id, 'oz_specialnost',true); ?></p><br>
					<?php if ($personal->post_content) : ?><div class="oz_text_cont"><?php echo $personal->post_content; ?></div><?php endif; ?>
					<div class="oz_btn"><?php _e('Select', 'book-appointment-online'); ?></div>
				</div>
			</li>
	<?php 
			endforeach; 
			wp_reset_postdata();
	?>
	</ul>
		<div id="timeForm">
			<div class="oz_form_wrap">
				<div class="oz_zapis_info">
					<p>
					<span style="display:none;" class="oz_spec_info"><?php _e('Specialist:', 'book-appointment-online'); ?> <span></span></span>
					<?php _e('Date', 'book-appointment-online'); ?>: <span class="oz_date_info"></span> <?php _e('at', 'book-appointment-online'); ?> <span class="oz_time_info"></span><br>
					<span class="oz_usluga_info"><?php _e('Service', 'book-appointment-online'); ?> : <span></span></span><br>
					<span><?php _e('Leave your contacts!', 'book-appointment-online'); ?></span>
					</p>
				</div>
					<form action="'<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="oz-form" novalidate="novalidate">
					<div>
					<input type="hidden" name="action" value="do_zapis" />
					<input type="hidden" class="chist" name="oz_start_date_field_id" value="" />
					<input type="hidden" class="chist" name="oz_personal_field_id" value="" />
					<input type="hidden" class="chist" name="oz_time_rot" value="" />
					<input type="hidden" class="chist" name="oz_filials" value="" />
					<input type="hidden" class="chist" name="oz_uslug_set" value="" />
					<input type="hidden" class="chist" name="oz_remList" value="" />
					
					</div>
					<div class="form_fields">
					<?php $oz_polya = get_option( 'oz_polya' ); ?>
						<label>
							<input type="text" name="clientName" size="40" class="" aria-required="true" placeholder="<?php _e('Name', 'book-appointment-online'); ?>" />
						</label>
						<?php if (isset($oz_polya['tel']) && $oz_polya['tel']) : ?>
						<label>
							<input type="tel" data-inputmask="'mask': '+9(999)999-99-99'" name="clientPhone" size="40" class="" <?php if (isset($oz_polya['tel']['req']) && $oz_polya['tel']['req']) : ?> aria-required="true" <?php endif; ?> placeholder="<?php _e('Phone', 'book-appointment-online'); ?>" />
						</label>
						<?php endif; ?>
						<?php if (isset($oz_polya['email']) && $oz_polya['email']) : ?>
						<label>
							<input type="text" data-inputmask="'alias': 'email'" name="clientEmail" size="40" class="" <?php if (isset($oz_polya['email']['req']) && $oz_polya['email']['req']) : ?>  aria-required="true" <?php endif; ?> placeholder="<?php _e('Email', 'book-appointment-online'); ?>" />
						</label>
						<?php endif; ?>
						<label class="text-center">
						<input type="submit" value="<?php _e('Booking', 'book-appointment-online'); ?>" class="oz_submit oz_btn" />
						</label>
						</div>
					</form>
				<div class="form-label">
				</div>
			</div>
		</div>
		<?php endif;  ?>
	</div>
	</div>
</div>
<?php $result = ob_get_contents(); ob_end_clean(); return $result; ?>