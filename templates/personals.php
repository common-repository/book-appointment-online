<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
// Personal front tenplate
 ?>
 			<li class="personal">
				<div class="oz_image">
					<?php if ( has_post_thumbnail($id_1) ) : the_post_thumbnail($id_1); else : ?><img src="<?php echo site_url(); ?>/wp-content/plugins/online-zapis/images/pers-ava.svg" alt="personal image" /><?php endif; ?>
				</div>
				<div class="pers-content">
					<p data-mintime="<?php echo $minpersTime; ?>" data-usl='<?php echo json_encode(get_post_meta($id_1, 'oz_re_timerange',true)); ?>' data-id="<?php echo $id_1;  ?>" data-days='<?php echo get_post_meta($id_1,'oz_raspis',true); ?>' data-raspis='<?php echo $clBr; ?>' class="pname"><?php echo get_the_title($id_1); ?></p>
					<p class="special"><?php echo get_post_meta($id_1, 'oz_specialnost',true); ?></p><br>
					<div class="oz_btn">Выбрать дату и время</div>
					<?php global $oz_timezone; date_default_timezone_set($oz_timezone['chk']); ?>
				</div>
			</li>

