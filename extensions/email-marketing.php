<?php
/**
 * @author    Ozplugin <client@oz-plugin.ru>
 * @link      http://www.oz-plugin.ru/
 * @copyright 2018 Ozplugin
 * @ver 1.37
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class BAO_Email {
	
	function __construct() {
	$this->hasChanges = false;
	add_action('register_book_oz_settings_advanced', array($this,'init_opts'));
	add_action('book_oz_send_ok', array($this,'sending_emails'),11);
	add_filter('book_oz_tabs_line', array($this,'tab'),9,1);
	add_action('book_oz_advanced_tabs', array($this,'tab_content'),10,2);
	add_filter('mce_external_plugins', array($this,'fullpage'));
	add_action( 'after_wp_tiny_mce', array($this,'script'),99 );
	add_action('wp_ajax_oz_def_email', array($this, 'default_template'));
	add_filter('book_oz_toclient_title',array($this,'toclientTitle'),11,2);
	add_filter('book_oz_toclient_mess',array($this,'toclientMess'),11,2);
	add_filter('book_oz_toadmin_title',array($this,'toadminTitle'),10,2);
	add_filter('book_oz_toadmin_mess',array($this,'toadminMess'),10,2);
	add_action('register_book_oz_settings_advanced', array($this,'option_from_name'));																			 																			 
	}
	
	/**
	 *  Ex function sending default emails after booking
	 *  
	 *  @return void
	 *  
	 *  @version 2.0.2
	 */
	public function sending_emails($idKlienta) {
		$email = (get_option('oz_default_email')) ? get_option('oz_default_email') : get_option('admin_email');
		$headers = 	'Content-Type: text/html; charset=utf-8' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		$send_ok = 0;
		add_filter('wp_mail_from',array($this,'wp_mail_from_email'),20,1);
		add_filter('wp_mail_from_name',array($this,'wp_mail_from_name'),20,1);
		$email_mess = $this->email_template('to_admin',$idKlienta);
		$date = date_i18n(get_option('date_format'),strtotime(get_post_meta($idKlienta,'oz_start_date_field_id',true)));
		$title = apply_filters('book_oz_toadmin_title',__('New booking on ', 'book-appointment-online').$date,$idKlienta);
		$canSendAdmin = apply_filters('book_oz_notify_on_send_ok_admin', get_option('oz_e_admin'), $idKlienta);
		if ($canSendAdmin) {
		$email_mess = apply_filters('book_oz_toadmin_mess',$email_mess,$idKlienta);
		$send_ok = wp_mail($email,$title,$email_mess ,$headers);
		}
		$canSend = apply_filters('book_oz_notify_on_send_ok', true, $idKlienta);
		if ($canSend) {
		do_action('book_oz_after_email_toAdmin',$idKlienta,$title,$email_mess ,$headers);
		$email = get_post_meta($idKlienta,'oz_clientEmail',true);
		$email_mess = $this->email_template('to_client',$idKlienta);
		$title = apply_filters('book_oz_toclient_title',__('Thank you for booking', 'book-appointment-online'),$idKlienta);
		$email_mess = apply_filters('book_oz_toclient_mess',$email_mess,$idKlienta);
		if (apply_filters('book_oz_email_to_client', get_option('oz_e_before'), $idKlienta))
		$send_ok = wp_mail($email,$title, $email_mess,$headers);
		}
	}
	
	/**
	 *  Return default email template without styles
	 *  
	 *  @param string    $komy Sending email client or admin
	 *  @param int    $idKlienta Client ID
	 *  @return void
	 *  
	 *  @version 2.0.2 (ex. function book_oz_email_template)
	 */
	public function email_template($komy,$idKlienta) {
	ob_start(); 
		include_once(plugin_dir_path( dirname(__FILE__) ).'/templates/emails/'.$komy.'.php');
		$mess = ob_get_contents();
		ob_end_clean();
	return $mess;
	}
	
	public function fields($return,$id = 0) {
		if ($return == 'tags')  {
		$return = ["%sitename%", "%date%", "%time%", "%id%", "%specialist%", "%service%", "%name%", "%phone%", "%duration%", "%email%"];
		$return = apply_filters('book_oz_tags_fields', $return);
		}
		if ($return == 'values') {
		$spec_id = get_post_meta($id,'oz_personal_field_id',true);
		$title_spec = get_the_title($spec_id);
		$title_usl = apply_filters('book_oz_uslugi_uslTitle',get_the_title(get_post_meta($id,'oz_uslug_set',true)),get_post_meta($id,'oz_uslug_set',true));
		$phone = get_post_meta($id,'oz_clientPhone',true);
		$time = apply_filters('book_oz_timeFormat',get_post_meta($id,'oz_time_rot',true));
		$date = date_i18n(get_option('date_format'),strtotime(get_post_meta($id,'oz_start_date_field_id',true)));
		$uslugi_id = get_post_meta($id,'oz_uslug_set',true);
		if (strpos($uslugi_id,',') !== false) {
			$uslugi = explode(',',$uslugi_id);
			$duration = 0;
			foreach ($uslugi as $usluga) $duration = $duration + (int) get_post_meta($usluga,'oz_serv_time',true);
			
		}
		else {
			$duration = get_post_meta($uslugi_id, 'oz_serv_time',true);
		}
		$email = get_post_meta($id,'oz_clientEmail',true);
		$return   = [site_url(), $date, $time, $id, $title_spec,$title_usl, get_the_title($id),$phone, $duration, $email];
		$return = apply_filters('book_oz_tags_fields', $return, $id, 'values');
		}
		return $return;
	}
	
	public function toclientTitle($title, $idKlienta) {
		if (get_option('oz_e_before') && get_option('oz_e_before_title')) {
		$title = apply_filters('book_oz_toclientTitle',get_option('oz_e_before_title'));
		$title = str_replace($this->fields('tags'), $this->fields('values',$idKlienta), $title);
		}
		return $title;
	}
	
	public function toclientMess($email_mess, $idKlienta) {
		if (get_option('oz_e_before') && get_option('oz_e_before_template')) {
		$email_mess = apply_filters('book_oz_toclientMess',get_option('oz_e_before_template'));
		$email_mess = str_replace($this->fields('tags'), $this->fields('values',$idKlienta), $email_mess);
		}
		return $email_mess;
	}
	
	public function toadminTitle($title, $idKlienta) {
		if (get_option('oz_e_admin') && get_option('oz_e_admin_title')) {
		$title = get_option('oz_e_admin_title');
		$title = str_replace($this->fields('tags'), $this->fields('values',$idKlienta), $title);
		}
		return $title;
	}
	
	public function toadminMess($email_mess, $idKlienta) {
		if (get_option('oz_e_admin') && get_option('oz_e_admin_template')) {
		$email_mess = get_option('oz_e_admin_template');
		$email_mess = str_replace($this->fields('tags'), $this->fields('values',$idKlienta), $email_mess);
		}
		return $email_mess;
	}
	
	
	/*add full html editor to tinymce*/
	public function fullpage ($plugins_array) {
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if ($screen->parent_base == 'book-appointment-online/book-appointment-online') {
			 $plugins_array[ 'fullpage' ] = plugins_url('js/tinymcefullpage.min.js', dirname(__FILE__));
			}
		}
		 return $plugins_array;
	}
	
	public function init_opts() {
		$this->options();
	}
	
	/* добавляем опции для email */
	private function options() {
		$opt_list = array(
		'oz_e_before',
		'oz_e_before_template',
		'oz_e_before_title',
		'oz_e_admin',
		'oz_e_admin_template',
		'oz_e_admin_title',
		'oz_e_remind',
		'oz_e_remind_template',
		'oz_e_remind_title',
		'oz_e_remind_min',
		'oz_e_thank',
		'oz_e_thank_template',
		'oz_e_thank_title',
		'oz_e_thank_min',
		'oz_e_register',
		'oz_e_register_template',
		'oz_e_register_title',
		'oz_e_remind_emp',
		'oz_e_remind_emp_template',
		'oz_e_remind_emp_title',
		'oz_e_remind_emp_min',
		'oz_e_rescheduled',
		'oz_e_rescheduled_template',
		'oz_e_rescheduled_title',
		);
		$opt_list = apply_filters('book_oz_email_opt_list', $opt_list);
		foreach ($opt_list as $opt) {
		register_setting('book_oz_settings', $opt);
		}
	}
	
	public static function get_include_contents($filename) {
		if (is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}
	
	/* добавляем вкладку для email */
	public function tab($tab) {
		$tab[] = array('id' => 'email-li', 'name' => __('Email marketing', 'book-appointment-online'), 'tabIcon' => 'dashicons-email' );
		return $tab;
	}
	
	public function on_send_text($template_name = null, $default = false) {
		$def = $this->get_include_contents(plugin_dir_path( dirname(__FILE__) ).'templates/email_'.$template_name.'.php');
		$text = $def;
		$tags = ["%sitename%"];
		$values   = [site_url()];
		if (!$default) {
			$text = get_option('oz_e_'.$template_name.'_template',$def);
		}
		$text = str_replace($tags, $values, $text);
		return $text;
	}
	
	public function default_template() {
		if (defined('DOING_AJAX') && DOING_AJAX) {
			if (isset($_POST['type']) && isset($_POST['tmpName'])) {
				$tmpName = sanitize_text_field($_POST['tmpName']);
				echo $this->on_send_text($tmpName,true);
			}
		}
		wp_die();
	}
	
	public function script() {
		?>
		<script>
	jQuery('.oz_set_defemail').click(function() {
		var id = jQuery(this).attr('data-id');
		var tmpName = id.replace('oz_e_','');
		var tmpName = tmpName.replace('_temp','');
		if (tinymce.get(id) !== null) {
		jQuery.ajax( {
		url:'<?php echo admin_url( 'admin-ajax.php' ); ?>',
		data:{'action':'oz_def_email', 'type': 'oz_e_before_template', 'tmpName': tmpName},
		method:'POST',
		success: function(response,status) {
			tinymce.get(id).setContent(response);
		},
		});
		}
	});
	jQuery('.oz_accordion').click(function() {
		var id = jQuery(this).attr('id');
		jQuery('#'+id+'_tab.oz_email_tabs').slideToggle();
		jQuery(this).toggleClass('active');
	});
</script>
		<?php
	}
	
	public function email_types() {
		$types = array(
			'oz_e_admin' => array(
			'name' => __('Email template about booking to the admin', 'book-appointment-online').' ('.(__('employee', 'book-appointment-online')).')',
			'description' => __('Email template when user has booked an appointment', 'book-appointment-online'),
			'title' => __('New booking on %date%', 'book-appointment-online')
			),
			'oz_e_before' => array(
			'name' => __('Email template on booking', 'book-appointment-online'),
			'description' => __('Email template when user has booked an appointment', 'book-appointment-online'),
			'title' => __('Thank you for booking', 'book-appointment-online')
			),
			'oz_e_remind' => array(
			'name' => __('Email template reminder', 'book-appointment-online'),
			'isPro' => true,
			),
			'oz_e_remind_emp' => array(
			'name' => __('Email template reminder (to employee, admin)', 'book-appointment-online'),
			'isPro' => true,
			),
			'oz_e_rescheduled' => array(
			'name' => __('Email template about reschedule', 'book-appointment-online'),
			'isPro' => true,
			),
			'oz_e_thank' => array(
			'name' => __('Email template after booking', 'book-appointment-online'),
			'isPro' => true,
			),
			'oz_e_register' => array(
			'name' => __('Email template on customer register', 'book-appointment-online'),
			'isPro' => true,
			),
		);
		return apply_filters('book_oz_email_types',$types);
	}
	
	/* добавляем вкладку email*/
	public function tab_content($oz_theme, $oz_vid) {
	?>
	<div id="email-li-tab" class="book_oz_tab">
			<table  class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="oz_default_email"><?php _e('Email to notification', 'book-appointment-online'); ?></label></th>
						<td>
						<input name="oz_default_email" type="text" id="oz_default_email" value="<?php echo get_option('oz_default_email'); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="oz_default_email_name"><?php _e('From name (name)', 'book-appointment-online'); ?></label></th>
						<td>
						<input name="oz_default_email_name" type="text" id="oz_default_email_name" value="<?php echo get_option('oz_default_email_name'); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="oz_email_from_email"><?php _e('From (email)', 'book-appointment-online'); ?><br><small><?php _e('E-mail must contain domain of your site. eg: ', 'book-appointment-online'); ?>noreply@<?php echo str_replace(array( 'http://', 'https://', 'www.' ), '', site_url()); ?></small></label></th>
						<td>
						<input name="oz_email_from_email" type="text" id="oz_email_from_email" value="<?php echo get_option('oz_email_from_email'); ?>" class="regular-text">
						</td>
					</tr>
				</tbody>
			</table>
	<?php
		$templates = $this->email_types();
		foreach ($templates as $id => $template) :
		$isPro = isset($template['isPro']) && $template['isPro'];
	?>
		<div id="<?php echo $id; ?>" class="oz_accordion <?php echo $isPro  ? 'only-pro-opac' : ''; ?>">
			<label><?php echo $template['name']; ?> <?php if ($isPro) :?><sup>Only in Pro</sup><?php endif; ?> <span class="dashicons dashicons-arrow-down-alt2"></span></label>
		</div>
		<?php if (!$isPro) : ?>
		<div id="<?php echo $id; ?>_tab" class="oz_email_tabs">
			<table  class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label>
								<?php _e('Enable', 'book-appointment-online'); ?>
							</label>
						</th>
						<td>
							<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="1"<?php checked( 1 == get_option($id) ); ?> />
						</td>
					</tr>
					<?php if ($id == 'oz_e_remind' || $id == 'oz_e_thank' || $id == 'oz_e_remind_emp') : ?>
					<tr>
						<th scope="row">
							<label>
								<?php echo $template['minutes']; ?>
							</label>
						</th>
						<td>
							<select id="<?php echo $id; ?>_min" name="<?php echo $id; ?>_min">
								<option value="30" <?php selected( get_option($id.'_min'), 30 ); ?>>30 <?php _e('minutes', 'book-appointment-online'); ?></option>
								<option value="60" <?php selected( get_option($id.'_min'), 60 ); ?>>1 <?php _e('hour', 'book-appointment-online'); ?></option>
								<option value="120" <?php selected( get_option($id.'_min'), 120 ); ?>>2 <?php _e('hours', 'book-appointment-online'); ?></option>
								<option value="240" <?php selected( get_option($id.'_min'), 240 ); ?>>4 <?php _e('hours', 'book-appointment-online'); ?></option>
								<option value="1440" <?php selected( get_option($id.'_min'), 1440 ); ?>>24 <?php _e('hours', 'book-appointment-online'); ?></option>
							</select>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<th scope="row">
							<label>
								<?php _e('Email title', 'book-appointment-online'); ?>
							</label>
						</th>
						<td>
							<input name="<?php echo $id; ?>_title" type="text" id="<?php echo $id; ?>_title" value="<?php echo get_option($id.'_title',$template['title']); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo $id; ?>_template"><?php echo $template['description']; ?></label><br><br>
							<div class="oz_email_shortcodes">
							<?php if ($id != 'oz_e_register') : ?>
							<span class="oz_code">%sitename%</span> - <?php _e('Site URL', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%date%</span> - <?php _e('Date booking', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%time%</span> - <?php _e('Time booking', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%id%</span> - <?php _e('ID of booking (post ID)', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%specialist%</span> - <?php _e('Staff name', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%service%</span> - <?php _e('Service name', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%name%</span> - <?php _e('Client name', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%phone%</span> - <?php _e('Client phone', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%email%</span> - <?php _e('Client email', 'book-appointment-online'); ?><br><br>
							<?php do_action('book_oz_description_email_shortcodes',$id); ?>
							<?php else : ?>
							<span class="oz_code">%name%</span> - <?php _e('Name', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%login%</span> - <?php _e('Customer login', 'book-appointment-online'); ?><br><br>
							<span class="oz_code">%pass%</span> - <?php _e('Password', 'book-appointment-online'); ?><br><br>
							<?php endif; ?>
							<?php
							$fields = get_option('oz_cust_fields');
							if ($fields && $id != 'oz_e_register') :
							?>
							<p><strong><?php _e('Custom fields', 'book-appointment-online'); ?></strong></p>
							<?php foreach ($fields as $field) :  ?>
							<span class="oz_code"><?php echo '%field-'.$field['meta'].'%'; ?></span> - <?php echo $field['name']; ?><br><br>
							<?php endforeach; endif; ?>
							</div>
							<div data-id="<?php echo $id; ?>_temp" class="oz_set_defemail button"><?php _e('Load default template', 'book-appointment-online'); ?></div>
						</th>
						<td id="<?php echo $id; ?>_template">
							<?php
							$editor_id = $id.'_temp';
							$temp_name = str_replace('oz_e_','',$id);
							wp_editor( $this->on_send_text($temp_name), $editor_id, array('textarea_name' => $id.'_template','editor_height' => 425,'wpautop' => 0, 'editor_css' => 0, ) );
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
		<?php endforeach; ?>
		<div style="padding:20px"></div>
	</div>
	<?php
	}
	/**
	 *  Option to email - Email from name
	 *  
	 *  @return void
	 *  
	 *  @version 2.0.2
	 */
	public function option_from_name() {
		register_setting('book_oz_settings', 'oz_email_from_email');
	}
	
	/**
	 *  Change default value - email from
	 *  
	 *  @param string    $name Email from
	 *  @return Return email from
	 *  
	 *  @version 2.0.2
	 */
	public function wp_mail_from_email($name) {
		$name = (get_option('oz_email_from_email')) ? get_option('oz_email_from_email') : $name;
		return $name;
	}
	
	/**
	 *  Change default value - email from name
	 *  
	 *  @param string    $name Email from name
	 *  @return Return email from name
	 *  
	 *  @version 2.0.2
	 */
	public function wp_mail_from_name($name) {
		$name = (get_option('oz_default_email_name')) ? get_option('oz_default_email_name') : get_bloginfo('name');
		return $name;
	}

}
new BAO_Email();
 ?>