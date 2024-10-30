<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action('book_oz_after_metabox','book_oz_calendarSot',10,2);
function book_oz_calendarSot($arg) {
	if ($arg == 'book_oz_calendarSot' && isset($_GET['post'])) {
$post = $_GET['post'];
do_action('book_oz_update_spisok_klientov',$post);
 ?>
			<?php
$arr = json_decode(get_post_meta($post,'oz_raspis',true),true);
if ($arr) :
$start = array_column($arr, 'start');
$end = array_column($arr, 'end');
$start =  min($start);
$end =  max($end);
endif;
			?>
<script>
var dayStart, dayFinish, hourStart, minStart, hourFinish, minFinish;
 <?php if (isset($start)) : ?>
dayStart = <?php echo '"'.$start.'"'; ?> ;
hourStart = <?php echo '"'.explode(':',$start)[0].'"'; ?> ;
<?php endif; ?>
<?php if (isset($end)) : ?>
dayFinish =  <?php echo '"'.$end.'"'; ?> ;
hourFinish = <?php echo '"'.explode(':',$end)[0].'"'; ?> ;
<?php endif; ?>
</script>
		<div id="calendar"></div>
		<?php
			}
		} 
		?>
<?php
function book_oz_rasp_obj() {
	global $post;
	$rasp = (get_post_meta($post->ID,'oz_raspis',true)) ? get_post_meta($post->ID,'oz_raspis',true) : '""';
	echo '<script> rasp = '.$rasp.'; postId = '.$post->ID.';</script>';
}
	
add_action('book_oz_before_metabox','book_oz_rasp_obj',10,1);

add_action('book_oz_before_metabox','book_oz_worktime',10,2);

function book_oz_worktime($arg) {

	if ($arg == 'book_oz_worktime' ) {
		?>
		<div class="oz_worktime_div">
			<table class="oz_worktime">
				<thead>
					<tr>
					<th><?php _e('Schedule', 'book-appointment-online'); ?></th>
					<th><?php _e('MO', 'book-appointment-online'); ?></th>
					<th><?php _e('TU', 'book-appointment-online'); ?></th>
					<th><?php _e('WE', 'book-appointment-online'); ?></th>
					<th><?php _e('TH', 'book-appointment-online'); ?></th>
					<th><?php _e('FR', 'book-appointment-online'); ?></th>
					<th><?php _e('SA', 'book-appointment-online'); ?></th>
					<th><?php _e('SU', 'book-appointment-online'); ?></th>
					<th></th>
					</tr>
				</thead>
				<tbody>
					<tr class="remove">
					<td colspan="7"><?php _e('Add work hours', 'book-appointment-online'); ?></td>
					<td></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>			
							<div class="oz_btn add-date button button-primary button-large"><span class="dashicons dashicons-plus"></span> <?php _e('Add new line', 'book-appointment-online'); ?></div>
						</td>
					</tr>
					<tr>
						<td colspan="9">
							<div class="add-block hide">
								<input type="text" class="at-time" name="oz_23" id="oz_ras_start" placeholder="<?php _e('Time start', 'book-appointment-online'); ?>" data-ampm='false' rel='hh:mm' value='' size='30'>
								<input type="text" class="at-time" name="oz_23" id="oz_ras_end" placeholder="<?php _e('Time end', 'book-appointment-online'); ?>" data-ampm='false' rel='hh:mm' value='' size='30'>
								<div class="add-date-time button button-primary button-large"><span class="dashicons dashicons-plus"></span> <?php _e('Add', 'book-appointment-online'); ?></div>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="plav_grafh hide">
				<div class="plav_time_text">
					<b></b>
					<span><?php _e('Edit', 'book-appointment-online'); ?></span>
				</div>
				<div class="plav_grafh_block hide">
					<p><?php _e('Shift work', 'book-appointment-online'); ?></p>
					<select id="oz_rab1" name="oz_rab1">
						<?php $i=0; while ($i < 7) : $i++; ?>
						   <option value="<?php echo $i; ?>"><?php echo ($i == 7) ? __('Week', 'book-appointment-online') : $i; ?></option>
						<?php endwhile; ?>
					</select>
								<?php _e('across', 'book-appointment-online'); ?>
					<select id="oz_rab2" name="oz_rab2">
						<?php $i=0; while ($i < 7) : $i++; ?>
						   <option value="<?php echo $i; ?>"><?php echo ($i == 7) ? __('Week', 'book-appointment-online') : $i; ?></option>
						<?php endwhile; ?>
					</select>
					<?php global $post; ?>
					<div class="oz_flex_container">
						<label>
							<input type="text" class="at-date" name="oz_first_day" id="oz_first_day" placeholder="<?php _e('Date of any first shift', 'book-appointment-online'); ?>" rel="dd.mm.yy" value="" size="30">
						</label>
						<label class="width48fl">
							<input type="text" class="at-time" name="oz_231" id="oz_ras_start1" placeholder="<?php _e('Time start', 'book-appointment-online'); ?>" data-ampm='false' rel='hh:mm' value='' size='30' />
						</label>
						<label class="width48fl">
							<input type="text" class="at-time" name="oz_231" id="oz_ras_end1" placeholder="<?php _e('Time end', 'book-appointment-online'); ?>" data-ampm='false' rel='hh:mm' value='' size='30' />
						</label>
						<div class="add-date-time button button-primary button-large"><span class="dashicons dashicons-plus"></span> <?php _e('Add', 'book-appointment-online'); ?></div>
					</div>
				</div>
			</div>
			</div>
<style>

.worktime {
	   border-collapse: collapse;
}

.warning {
	border:1px solid red !important;
}

.hide {
	display:none;
}
</style>
		<?php
	}
}

add_action('book_oz_before_metabox','book_oz_servListSelect',11,1);

function book_oz_servListSelect($arg) {
	global $post;
	if ($arg == 'book_oz_worktime' ) {
	?>
<table id="book_oz_servListSelect" class="form-table">
	<tr>
		<td class='at-field'>
			<div class='at-label'>
				<label for='oz_re_timerange'><?php _e('Provides services', 'book-appointment-online'); ?></label>
			</div>
			<?php $cur = get_post_meta($post->ID,'oz_book_provides_services',true); ?>
			<select class="oz_book_select_block at-posts-select-exclude" id="oz_book_provides_services" name="oz_book_provides_services" onchange="oz_book_changeSelect(this.value);">
				<option value="all" 	<?php selected($cur, 'all' ); ?>><?php _e('All services', 'book-appointment-online'); ?></option>
				<option value="exclude" <?php selected($cur, 'exclude' ); ?>><?php _e('Exclude services below', 'book-appointment-online'); ?></option>
				<option value="include" <?php selected($cur, 'include' ); ?>><?php _e('Include services below', 'book-appointment-online'); ?></option>
			</select>
		</td>
	</tr>
</table>
	<?php
	}
}

add_action('save_post', 'book_oz_servListSelectSave');

function book_oz_servListSelectSave(){ 
    global $post;
	//echo mail('avito1@aleksinsky.ru','test',print_r($_POST,1));
    if(isset($_POST["oz_book_provides_services"])){
        $prov = sanitize_text_field($_POST['oz_book_provides_services']);
        update_post_meta($post->ID, 'oz_book_provides_services', $prov);
        //print_r($_POST);
    }
}