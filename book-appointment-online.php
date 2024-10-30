<?php
/*
Plugin Name: Book appointment online
Plugin URI: http://demo.oz-plugin.ru/
Description: Appointment online to a specialist. Perfectly suitable for beauty salons, medical centers, car services, etc.
Requires at least: 4.9
Tested up to: 5.7
Version: 1.39
Author: oz-plugin
Author URI: http://demo.oz-plugin.ru/
License: GPLv2
Text Domain: book-appointment-online
*/

define( 'BOOKOZ_VER', '1.39' );
require_once(plugin_dir_path( __FILE__ ).'class/custom-meta-box.php');
require_once(plugin_dir_path( __FILE__ ).'inc/meta-boxes.php');
require_once(plugin_dir_path( __FILE__ ).'inc/functions-front.php');
require_once(plugin_dir_path( __FILE__ ).'inc/extensions.php');

add_action( 'plugins_loaded', 'book_oz_plugin_load_textdomain' );
function book_oz_plugin_load_textdomain() {
  load_plugin_textdomain( 'book-appointment-online', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

function book_oz_call_inc() {
	$current = get_current_screen();
    if( 'personal' == $current->post_type ) {
	require_once(plugin_dir_path( __FILE__ ).'inc/personal.php');	
	}
	
	if( 'clients' == $current->post_type ) {
	require_once(plugin_dir_path( __FILE__ ).'inc/clients.php');
	}
}
if ( is_admin() ) {
    add_action( 'load-post.php',     'book_oz_call_inc' );
    add_action( 'load-post-new.php', 'book_oz_call_inc' );
}

$oz_theme =  get_option('oz_theme');
$oz_vid = get_option('oz_vid');


function book_oz_back_scripts() {
        wp_register_style( 'oz_admin_css', plugins_url('/css/oz_admin_css.css', __FILE__), false, BOOKOZ_VER );
        wp_enqueue_style( array('oz_admin_css') );
}
add_action( 'admin_enqueue_scripts', 'book_oz_back_scripts' );


add_action('admin_menu', 'book_oz_create_menu');

function book_oz_create_menu() {
	add_menu_page(__('Booking settings', 'book-appointment-online'), __('Booking settings', 'book-appointment-online'), 'administrator', __FILE__, 'book_oz_page','dashicons-admin-settings',8);
	add_action( 'admin_init', 'register_book_oz_settings' );
}

add_filter('option_page_capability_book_oz_settings','book_oz_editor_can_manage',22,1);

function book_oz_editor_can_manage($cap) {
if (current_user_can('edit_pages')) {
	return 'edit_pages';
}
}
function register_book_oz_settings() {
	register_setting('book_oz_settings', 'oz_theme'); // тема
	register_setting('book_oz_settings', 'oz_default_email'); // дефолт email для оповещений
	register_setting('book_oz_settings', 'oz_default_email_name'); // имя отправителя
	register_setting('book_oz_settings', 'oz_default_cur'); // валюта
	register_setting('book_oz_settings', 'oz_polya', array('default' => array('tel' => array('name' => 1,'req' =>1),'email'=> array('name' => 1,'req' =>1)))); // дефолт email для оповещений
	register_setting('book_oz_settings', 'oz_redirect_url');
	register_setting('book_oz_settings', 'oz_colors'); // colors
	do_action('register_book_oz_settings_advanced');
}

//add_action( 'admin_init', 'oz_activate' );


function book_oz_wp_admin_style($hook) {
        // Load only on ?page=mypluginname
	$current = get_current_screen();
        if( ($current->post_type == 'clients' && $current->base == 'post') || ($current->post_type == 'personal' && $current->base == 'post') || $current->base == 'dashboard' ) {

        wp_enqueue_style( 'book_oz_fullcalendar', plugins_url('/css/fullcalendar.min.css', __FILE__) );
		if (!wp_script_is('jquery-ui-datepicker')) wp_enqueue_script( 'jquery-ui-datepicker');
		wp_enqueue_script( 'book_oz_moment', plugins_url('/js/moment.min.js', __FILE__),array( 'jquery-ui-core' ) );
		wp_enqueue_script( 'book_oz_fullcalendar', plugins_url('/js/fullcalendar.min.js', __FILE__),array( 'jquery-ui-core' ) );
		wp_enqueue_script( 'book_oz_locale-all', plugins_url('/js/locale-all.js', __FILE__),array( 'jquery-ui-core' ) );
		wp_enqueue_script( 'ozscripts', plugins_url('/js/ozscripts.js', __FILE__),array( 'jquery-ui-core' ), BOOKOZ_VER, true ); //закомментил чтобы работало на главной
		$oz_vars = array(
		'adminAjax' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('myajax-nonce'),
		'post_type' => ($current->base == 'post') ? get_post_type() : 'none',
		'dateFormat' => convertPHPToMomentFormat(get_option('date_format')), 
		'timeFormat' => convertPHPToMomentFormat(get_option('time_format')), 
		'lang' => get_locale()
		);
		$oz_vars = apply_filters('book_custAdmin_JSOptions',$oz_vars);
		wp_localize_script( 'ozscripts', 'oz_vars', $oz_vars );
		$oz_alang = array(
		'str1' => __('First work day:', 'book-appointment-online'), 
		'str2' => __('Contact with developers!', 'book-appointment-online'), 
		'str3' => __('Add work hours', 'book-appointment-online'),
		'str4' => __('from', 'book-appointment-online'),
		'str5' => __('before', 'book-appointment-online'), 
		'str6' => __('First work day:', 'book-appointment-online')		
		);
		wp_localize_script( 'ozscripts', 'oz_alang', $oz_alang );
		        }
				
}
add_action( 'admin_enqueue_scripts', 'book_oz_wp_admin_style' );

function book_oz_wp_localize_jquery_ui_datepicker() {
	global $wp_locale;

	if ( ! wp_script_is( 'ozscripts', 'enqueued' ) && ! wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) {
		return;
	}

	// Convert the PHP date format into jQuery UI's format.
	$datepicker_date_format = str_replace(
		array(
			'd', 'j', 'l', 'z', // Day.
			'F', 'M', 'n', 'm', // Month.
			'Y', 'y'            // Year.
		),
		array(
			'dd', 'd', 'DD', 'o',
			'MM', 'M', 'm', 'mm',
			'yy', 'y'
		),
		get_option( 'date_format' )
	);

	$datepicker_defaults = wp_json_encode( array(
		'closeText'       => __( 'Close' ),
		'currentText'     => __( 'Today' ),
		'monthNames'      => array_values( $wp_locale->month ),
		'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
		'nextText'        => __( 'Next' ),
		'prevText'        => __( 'Previous' ),
		'dayNames'        => array_values( $wp_locale->weekday ),
		'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
		'dayNamesMin'     => array_values( $wp_locale->weekday_initial ),
		'dateFormat'      => $datepicker_date_format,
		'firstDay'        => absint( get_option( 'start_of_week' ) ),
		'isRTL'           => $wp_locale->is_rtl(),
	) );

	wp_add_inline_script( 'ozscripts', "if (typeof jQuery.datepicker !== 'undefined') { var book_oz_setLang = {$datepicker_defaults}; jQuery.datepicker.setDefaults(book_oz_setLang);}",'before' );
}
add_action( 'admin_enqueue_scripts', 'book_oz_wp_localize_jquery_ui_datepicker',10 );

function book_oz_page() {
	global $oz_theme, $oz_vid;
	?>
	<div class="wrap">
		<h2><?php _e('Booking settings', 'book-appointment-online'); ?></h2>
		<form method="post" action="options.php" id="oz_settings">
		<?php settings_fields('book_oz_settings'); ?>
		<?php 
			$liTabs = array(); 
			$liTabs = apply_filters('book_oz_tabs_line',$liTabs);
		?>
		<ul class="book_oz_tabs">
			<li class="active" id="main-li"><span class="dashicons dashicons-admin-tools"></span> <?php _e('Main settings','book-appointment-online') ?></li>
			<?php if ($liTabs) : foreach ($liTabs as $key => $liTab) :
				$liId = (isset($liTab['id'])) ? $liTab['id'] : 'liId-'.$liTab[$key];
				$liName = (isset($liTab['name'])) ? $liTab['name'] : __('Not select','book-appointment-online');
				$liIcon = (isset($liTab['tabIcon'])) ? $liTab['tabIcon'] : '';
			?>
			<li id="<?php echo $liId; ?>"><?php if ($liIcon) : ?><span class="dashicons <?php echo $liIcon; ?>"></span><?php endif; ?> <?php echo $liName; ?></li>
			<?php endforeach; endif;
				if(!isset($_COOKIE) || !isset($_COOKIE['oz_hide']) || $_COOKIE['oz_hide'] != 'y') :
			?>
						<li id="appointment-li"><span class="dashicons dashicons-admin-tools"></span> Appointment settings<sup>Only in PRO</sup></li>
						<li id="payment-li"><span class="dashicons dashicons-cart"></span> Payment options<sup>Only in PRO</sup></li>
						<li id="sms-li"><span class="dashicons  dashicons-format-chat"></span> SMS Notification <sup>Only in PRO</sup></li>
						<li id="integration-li"><span class="dashicons dashicons-admin-plugins"></span> Integration<sup>Only in PRO</sup></li>
				<?php endif; ?>
		</ul>
		<?php if(!isset($_COOKIE) || !isset($_COOKIE['oz_hide']) || $_COOKIE['oz_hide'] != 'y') require_once(plugin_dir_path( __FILE__ ).'inc/pro-present.php'); do_action('book_oz_advanced_tabs',$oz_theme, $oz_vid); ?>
		<div id="main-li-tab" class="book_oz_tab active">
		<table class="form-table">
			<tbody>
				<tr class="oz_as_shortcode">
					<th scope="row"><label for="oz_how"><?php _e('How to place', 'book-appointment-online'); ?></label></th>
					<td class="welcome-panel">
					<span class=""><?php _e('Add shortcode to page', 'book-appointment-online'); ?>: <code>[oz_template]</code></span><br><br>
					<span class=""><?php _e('Add php code to template', 'book-appointment-online'); ?>: <code><?php echo htmlspecialchars("<?php echo do_shortcode('[oz_template]');?>"); ?></code></span>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="oz_theme"><?php _e('Template', 'book-appointment-online'); ?></label></th>
					<td>
					<select name="oz_theme[chk]">
						<option value="default" <?php selected( $oz_theme['chk'], 'default' ); ?>><?php _e('Default', 'book-appointment-online'); ?></option>
						<option value="no_styles" <?php selected( $oz_theme['chk'], 'no_styles' ); ?>><?php _e('No styles', 'book-appointment-online'); ?></option>
					</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="oz_default_cur"><?php _e('Currency', 'book-appointment-online'); ?></label></th>
					<td>
					<input name="oz_default_cur" type="text" id="oz_default_cur" value="<?php echo get_option('oz_default_cur'); ?>" class="regular-text">
					</td>
				</tr>
					<tr>
						<th scope="row">
							<label for="oz_redirect_url">Redirect to this URL after successful appointment</label><br>
						</th>
						<td style="vertical-align:top;">
							<input name="oz_redirect_url" type="text" id="oz_redirect_url" value="<?php if (get_option('oz_redirect_url')) echo get_option('oz_redirect_url'); ?>">
						</td>
					</tr>
				<?php do_action('book_oz_add_main_options'); ?>
			</tbody>
		</table>
		</div>
			<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
		</form>
	</div>
	<script>
		/* переключаем табы навигации */

	jQuery('.book_oz_tabs li').click(function() {
		jQuery('.book_oz_tabs li').removeClass('active');
		jQuery(this).addClass('active');
		var tab = jQuery(this).attr('id');
		jQuery('.book_oz_tab').removeClass('active');
		jQuery('#'+tab+'-tab').addClass('active');
	});
	
	
	jQuery('.only-pro-div span').click(function() {
	jQuery('.only-pro-div').hide()
	document.cookie = "oz_hide=y; path=/wp-admin; max-age="+(3600*24*30)
	})
	</script>
	<?php
}

add_filter('post_row_actions', 'book_oz_remove_duplic',99,2);

function book_oz_remove_duplic($actions, $post) {
	if ($post->post_type == 'personal') {
	unset($actions['edit_as_new_draft']);
	unset($actions['clone']);
	}
	return $actions;
}

add_action('init', 'book_oz_create_post_type');
function book_oz_create_post_type() {
$args = array('numberposts' => -1, 'post_type' => 'personal', 'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
$pers = get_posts($args);
$argsPers =  array(
			'labels' => array(
				'name' => __('Employees', 'book-appointment-online'),
				'singular_name' => __('Employe', 'book-appointment-online'),
				'add_new' => __('Add employe', 'book-appointment-online'),
				'add_new_item' => __('Add employe', 'book-appointment-online'),
				'edit' => __('Edit employe', 'book-appointment-online'),
				'edit_item' => __('Edit employe', 'book-appointment-online'),
				'new_item' => __('New employe', 'book-appointment-online'),
				'view' => __('View employe', 'book-appointment-online'),
				'view_item' => __('View employe', 'book-appointment-online'),
				'search_items' => __('Search employe', 'book-appointment-online'),
				'not_found' => __('Employe not found', 'book-appointment-online'),
				'not_found_in_trash' => __('Employees not found in trash', 'book-appointment-online'),
				'parent' => __('Parent employe', 'book-appointment-online'),
			),
			'taxonomies' => array( '' ),
			'show_ui' => true,
			'public' => true,
			'menu_position' => 7,
			'supports' => array( 'title','editor','thumbnail' ),
			'menu_icon' => 'dashicons-businessman',
			'has_archive' => false,
			'exclude_from_search' => true, 		
		);
	register_post_type( 'personal',$argsPers);
	
	
	register_post_type( 'clients',
		array(
			'labels' => array(
				'name' => __('Clients', 'book-appointment-online'),
				'singular_name' => __('Client', 'book-appointment-online'),
				'add_new' => __('Add client', 'book-appointment-online'),
				'add_new_item' => __('Add client', 'book-appointment-online'),
				'edit' => __('Edit client', 'book-appointment-online'),
				'edit_item' => __('Edit client', 'book-appointment-online'),
				'new_item' => __('New client', 'book-appointment-online'),
				'view' => __('View client', 'book-appointment-online'),
				'view_item' => __('View client', 'book-appointment-online'),
				'search_items' => __('Search client', 'book-appointment-online'),
				'not_found' => __('Clients not found', 'book-appointment-online'),
				'not_found_in_trash' => __('Clients not found in trash', 'book-appointment-online'),
				'parent' => __('Parent client', 'book-appointment-online'),
			),
			'public' => true,
			'menu_position' => 8,
			'supports' => array( 'title' ),
			'menu_icon' => 'dashicons-groups',
			'has_archive' => false,
			'exclude_from_search' => true, 
		)
	);
	
register_post_type( 'services',
		array(
			'labels' => array(
				'name' => __('Services', 'book-appointment-online'),
				'singular_name' => __('Service', 'book-appointment-online'),
				'add_new' => __('Add service', 'book-appointment-online'),
				'add_new_item' => __('Add service', 'book-appointment-online'),
				'edit' => __('Edit service', 'book-appointment-online'),
				'edit_item' => __('Edit service', 'book-appointment-online'),
				'new_item' => __('New service', 'book-appointment-online'),
				'view' => __('View service', 'book-appointment-online'),
				'view_item' => __('View service', 'book-appointment-online'),
				'search_items' => __('Search service', 'book-appointment-online'),
				'not_found' => __('Services not found', 'book-appointment-online'),
				'not_found_in_trash' => __('Service not found', 'book-appointment-online'),
				'parent' => __('Parent service', 'book-appointment-online'),
			),
			'public' => true,
			'menu_position' => 6,
			'supports' => array( 'title','editor' ),
			'menu_icon' => 'dashicons-backup',
			'has_archive' => false,
			'exclude_from_search' => true, 
		)
	);
}

/*добавлена 01.04.18 проверка кто на выбранный день записан*/
add_action('wp_ajax_checkCurrentZapisi', 'book_oz_checkCurrentZapisi');
function book_oz_checkCurrentZapisi() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
	if (isset($_POST['dateText'])) {
	$zapisi = array();
	$currentClient = (isset($_POST['currentClient'])) ? $_POST['currentClient'] : ''; // исключаем текущую запись если это текущий клиент
	$dateText = $_POST['dateText']; // oz_start_date_field_id
	$args = array(
		'posts_per_page'   => -1,
		'meta_key'       => 'oz_start_date_field_id', // oz_personal_field_id
		'meta_value'       => $dateText,
		'post_type'        => 'clients',
		'post_status'      => 'publish',
		'exclude'		   => array($currentClient),
		'suppress_filters' => true 
	);
		$posts_array = get_posts( $args );
		if ($posts_array) {
			foreach ($posts_array as $post) {
				$idServ = get_post_meta($post->ID,'oz_uslug_set',true);
				$zapisi[] = array(
						'start' => get_post_meta($post->ID,'oz_time_rot',true),
						'pId'	=> get_post_meta($post->ID,'oz_personal_field_id',true),
						'w_time' => get_post_meta($idServ,'oz_serv_time',true), 
						);
			}
		}
		echo json_encode($zapisi,JSON_UNESCAPED_SLASHES);
	}
	}
	wp_die();
}

add_action('wp_ajax_checkSvobTime', 'book_oz_checkSvobTime'); // проверяем хватит ли времени до ближайшей следующей записи
function book_oz_checkSvobTime() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
	$nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : '';
	check_ajax_referer( 'myajax-nonce', 'nonce' );
	//if (isset($_POST)) echo print_r($_POST,1);
	$spec = (int) ($_POST['spec']);
	$post_ID = isset($_POST['post_ID']) ? (int) ($_POST['post_ID']) : '';
	$date = $_POST['date'];
	$time = $_POST['time'];
	$servtime = $_POST['servtime'];
	$zapisi = json_decode(get_post_meta($spec,'oz_clientsarray',true),true);
	/*чтобы данная проверка не работала для текущего клиента*/
	$ChosenClientTime = ($post_ID) ? get_post_meta($post_ID,'oz_start_date_field_id',true).' '.get_post_meta($post_ID,'oz_time_rot',true) : '' ;
	$ok = 'ok';
	$count = count($zapisi);
	$i = 0;
	if ($zapisi) {
		foreach ($zapisi as $zapis) {
			if (strpos($zapis['start'],$date) !== false) {
			$clos_zapis = DateTime::createFromFormat('d.m.Y H:i', $zapis['start']);
			$tec_zapis = DateTime::createFromFormat('d.m.Y H:i', $date.' '.$time);
			$raznica = ($clos_zapis->format('U') - $tec_zapis->format('U'))/60;
		if ($raznica > 0 && $raznica < $servtime) {
				$min = $servtime - $raznica;
				$ok = sprintf( __( 'Closest time to booking for current service is %s minute early', 'book-appointment-online-pro' ), $min );
			}
			else {
				$i++;
			}
			}
			
			if ($zapis['start'] == $ChosenClientTime ) {
			$ok = 'ok';	
			}
		}
	}
	echo $ok;
	}
	wp_die();
}

add_action('wp_ajax_checkRas', 'book_oz_checkRasAX');
function book_oz_checkRasAX() {
if (defined('DOING_AJAX') && DOING_AJAX) {
$nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : '';
$id = $_GET['id'];
$arr = json_decode(get_post_meta($id,'oz_raspis',true),true);
$zapisi = get_post_meta($id,'oz_clientsarray',true);
if ($arr) :
$start = array_column($arr, 'start');
$end = array_column($arr, 'end');
$start =  min($start);
$end =  max($end);
$raspi = get_post_meta($id,'oz_re_timerange',true);
$prom = array_column($raspi,'oz_select_time_serv');
$prom = ($prom) ? min($prom) : '';
$prom = (isset($prom)) ? ',"prom":"'.$prom.'"' : '';
$res = '[{"start":"'.$start.'","end":"'.$end.'"'.$prom.'},'.$zapisi.']';
else :
	$res = 'nothing';
endif;
check_ajax_referer( 'myajax-nonce', 'nonce' );
	echo $res;
}
	wp_die();
}

function book_oz_filial_add_custom_fields( $term ) {
	$t_id = $term->term_id;
	$term_filial_address = get_term_meta( $t_id, 'oz_filial_address', true ); 
	?>
<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="description">
			<?php _e('Address', 'book-appointment-online'); ?>
			</label>
		</th>
		<td>
			<input type="text" name="oz_filial_address" id="oz_filial_address" value="<?php echo esc_attr( $term_filial_address ) ? esc_attr( $term_filial_address ) : ''; ?>">
		</td>
	</tr>
</table>

<?php
}
add_action( 'filial_edit_form_fields', 'book_oz_filial_add_custom_fields', 1, 2 );

function book_oz_save_filial_custom_fields( $term_id ) {  
    if ( isset( $_POST['oz_filial_address'] ) ) { 
		$term_filial_address = sanitize_text_field($_POST['oz_filial_address']);	
        update_term_meta( $term_id, 'oz_filial_address', $term_filial_address );    
    }  
}
add_action( 'edited_filial', 'book_oz_save_filial_custom_fields', 10, 2 ); 
add_action( 'create_filial', 'book_oz_save_filial_custom_fields', 10, 2 ); 


remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

add_filter('filial_edit_form_fields', 'book_oz_cat_description');
function book_oz_cat_description($tag)
{
    ?>
        
<table class="form-table">
<tr class="form-field">
<th scope="row" valign="top">
<label for="description">
<?php _e('Description', 'book-appointment-online'); ?>
</label>
</th>
<td>

                <?php
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
                    wp_editor(wp_kses_post($tag->description , ENT_QUOTES, 'UTF-8'), 'filial_description', $settings);
                ?>                
<br/>
<span class="description">
<?php _e('The description is not prominent by default; however, some themes may show it.', 'book-appointment-online'); ?>
		</span>
		</td>
	</tr>
</table>

    <?php
}

add_action('admin_head', 'book_oz_remove_default_category_description');
function book_oz_remove_default_category_description()
{
    global $current_screen;
    if ( $current_screen->id == 'edit-filial' )
    {
    ?>
        
<script type="text/javascript">
        jQuery(function($) {
            $('textarea#description').closest('tr.form-field').remove();
        });
        
</script>

    <?php
    }
}

 function book_oz_cpt_services_columns( $columns ) {
    $columns["oz_price_td"] = __("Price",'book-appointment-online');
	$columns["oz_time_td"] = __("time (min)",'book-appointment-online');
    return $columns;
}
add_filter('manage_edit-services_columns', 'book_oz_cpt_services_columns');
add_filter('manage_edit-services_sortable_columns', 'book_oz_cpt_services_columns');

function book_oz_cpt_services_column( $colname, $cptid ) {
     if ( $colname == 'oz_time_td')
          echo esc_html(get_post_meta( $cptid, 'oz_serv_time', true ));
     if ( $colname == 'oz_price_td')
          echo esc_html(get_post_meta( $cptid, 'oz_serv_price', true ));
}
add_action('manage_services_posts_custom_column', 'book_oz_cpt_services_column', 10, 2); 

 function book_oz_cpt_columns( $columns ) {
    $columns["oz_date_td"] = __("Booking date",'book-appointment-online');
	$columns["oz_time_td"] = __("Booking time",'book-appointment-online');
	$columns["oz_pers_td"] = __("Specialist",'book-appointment-online');
    return $columns;
}
add_filter('manage_edit-clients_columns', 'book_oz_cpt_columns');
add_filter('manage_edit-clients_sortable_columns', 'book_oz_cpt_columns');

function book_oz_cpt_column( $colname, $cptid ) {
     if ( $colname == 'oz_date_td')
          echo date_i18n(get_option('date_format'),strtotime(get_post_meta( $cptid, 'oz_start_date_field_id', true )));
     if ( $colname == 'oz_time_td')
          echo date_i18n(get_option('time_format'),strtotime(get_post_meta( $cptid, 'oz_time_rot', true )));
     if ( $colname == 'oz_pers_td')
          echo get_the_title(get_post_meta( $cptid, 'oz_personal_field_id', true ));
}
add_action('manage_clients_posts_custom_column', 'book_oz_cpt_column', 10, 2); 

add_action('wp_ajax_saveDropChange', 'book_oz_saveDropChange');
function book_oz_saveDropChange() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
	$id = (isset($_POST['id'])) ? (int) ($_POST['id']) : '';
	$date = (isset($_POST['date'])) ? explode(' ', sanitize_text_field($_POST['date'])) : '';
	update_post_meta($id,'oz_start_date_field_id',$date[0]);
	update_post_meta($id,'oz_time_rot',$date[1]);
	}
	wp_die();
}

function book_oz_front_scripts() {
	global $post, $oz_theme, $oz_vid;
	$min = (!current_user_can( 'administrator' )) ? '.min' : '';
	if( ($post && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'oz_template')) || (isset($oz_vid['chk']) && $oz_vid['chk'] == 'as_popbtn')  ) {
	if ( $oz_theme['chk'] == 'default' || $oz_theme['chk'] == '' ) {
	wp_register_style( 'oz_default', plugins_url( '/css/default.theme.css', __FILE__ ),array(),BOOKOZ_VER );
	wp_enqueue_style( 'oz_default' );
	}
	wp_register_style( 'oz_front_css', plugins_url( '/css/oz_front_css.css', __FILE__ ),array(),BOOKOZ_VER );
	wp_enqueue_style( 'oz_front_css' );
    wp_enqueue_script( 'book_oz_csstojson', plugins_url('/js/csstojson.min.js', __FILE__),array( 'jquery' ), false, true );
	wp_enqueue_script( 'book_oz_inputmask', plugins_url('/js/jquery.inputmask.bundle.min.js', __FILE__),array('jquery'), false, true );
	wp_enqueue_script( 'book_oz_inputmask-phone', plugins_url('/js/phone.min.js', __FILE__),array('jquery'), false, true );
	wp_enqueue_script( 'book_oz_inputmask-phone-ru', plugins_url('/js/phone-ru.min.js', __FILE__),array('jquery'), false, true );
	wp_enqueue_script( 'book_oz_moment', plugins_url('/js/moment.min.js', __FILE__) );
	wp_enqueue_script( 'oz_front_scripts', plugins_url('/js/oz_front_scripts'.$min.'.js', __FILE__),array('jquery'), BOOKOZ_VER, true );	
	$oz_vars = array(
	'oz_ajax_url' => admin_url('admin-ajax.php'),
	);
	$oz_vars = apply_filters('book_custFront_JSOptions',$oz_vars);
	wp_localize_script( 'oz_front_scripts', 'oz_vars', $oz_vars );
	$oz_lang = array(
	'str1' => __('You booked!', 'book-appointment-online'), //304
	'str2' => __('Morning', 'book-appointment-online'), //436
	'str3' => __('Day', 'book-appointment-online'), //440
	'str4' => __('Evening', 'book-appointment-online'), //444
	'str5' => __('Contact information', 'book-appointment-online'), //604
	'str6' => __('Select service', 'book-appointment-online'),
	'str7' => __('Confirm booking', 'book-appointment-online'),
	'str8' => __('Select time booking', 'book-appointment-online'),
	'str9' => __('Select date', 'book-appointment-online'),
	'str10' => __('Select specialist', 'book-appointment-online'),
	'str11' => __('Service not worked at now', 'book-appointment-online'),
	'str12' => __('Skip selecting specialist', 'book-appointment-online'),
	
	);
	wp_localize_script( 'oz_front_scripts', 'oz_lang', $oz_lang );
	}
}

add_action( 'wp_enqueue_scripts', 'book_oz_front_scripts' );

function book_oz_template_func( $atts ) {
	$result = include_once(plugin_dir_path( __FILE__ ).'templates/shortcode.php');
	return $result;
}
add_shortcode( 'oz_template', 'book_oz_template_func' );

add_action( 'admin_init', 'book_oz_update_spisok_klientov_on_delete_init' );
function book_oz_update_spisok_klientov_on_delete_init() {
    add_action( 'trashed_post', 'book_oz_update_spisok_klientov_on_delete',10,1 );
	add_action('untrash_post', 'book_oz_update_spisok_klientov_on_delete',10,1);
	add_action('save_post', 'book_oz_update_spisok_klientov_on_delete',10,1);
	add_action('transition_post_status','book_oz_post_idFromStatus_action', 10, 3 ); // если меняем статус черновика, обновляем список записанных у специалиста
	add_action('book_oz_post_idFromStatus','book_oz_update_spisok_klientov_on_delete', 10, 2 );
}

function book_oz_post_idFromStatus_action($new_status, $old_status, $post) {
	do_action('book_oz_post_idFromStatus',$post->ID,'clients');
}

function book_oz_update_spisok_klientov_on_delete( $postid, $setPostType = false ){
    global $post_type;   
    if ( $post_type != 'clients' && !$setPostType ) return;
	$post = get_post_meta($postid, 'oz_personal_field_id',true);
	if ($post) {
		do_action('book_oz_update_spisok_klientov',$post);
	}
}

add_action('book_oz_update_spisok_klientov','book_oz_update_spisok_klientov_func',10,1);

/*
обновляем oz_clientsarray - список клиентов записанных к сотруднику
*/
function book_oz_update_spisok_klientov_func($post) {
$args = array(
	'posts_per_page'   => -1,
	'meta_value'       => $post, // oz_personal_field_id
	'post_type'        => 'clients',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
		$posts_array = get_posts( $args );
 
$cli = array();
$cliNotFormat = array();
$raspi = get_post_meta($post,'oz_re_timerange',true);
$prom = ($raspi) ? array_column($raspi,'oz_select_time_serv') : '';
$prom = ($prom) ? min($prom) : '';
$format = "d.m.Y H:i";
$timeF = "H:i";
 foreach ($posts_array as $clients) {
	 $date =  get_post_meta($clients->ID,'oz_start_date_field_id',true);
	 $start =  get_post_meta($clients->ID,'oz_time_rot',true);
	 $tel =	get_post_meta($clients->ID,'oz_clientPhone',true);
	 $u = get_post_meta($clients->ID,'oz_uslug_set',true);
	 $usl = ($u) ? get_the_title($u) : '';
	 $w_time = ($u) ? get_post_meta($u,'oz_serv_time',true) : '';
	 $start = strtotime($start);
	 $start = date('H:i', $start);
	 $end = strtotime("+".$prom." minutes", strtotime($start));
	 $end = date('H:i', $end);

$start = $date.' '.$start;
$end = $date.' '.$end;
$cliNotFormat[] = array(
'title' => $clients->post_title,
'start' => $start,
'w_time' => $w_time
);
if ($date) {
$startT = DateTime::createFromFormat($format, $start)->format(DateTime::ATOM);
$endT = strtotime("+".$w_time." minutes", strtotime($startT));
$endT = date('c',$endT);
$cli[] = array(
'title' => $clients->post_title,
'start' => $startT,
'end' => $endT,
'tel' => $tel,
'usl' => $usl,
'id' =>	 $clients->ID
);
}
 }
 $clients = json_encode($cli,JSON_UNESCAPED_SLASHES);
  if ($clients) {
$oz_vars = $clients;
wp_localize_script( 'ozscripts', 'clients', $cli );
$lan = '';
if (get_locale() == 'ru_RU' ) {
	$lan = 'ru';
}
wp_localize_script( 'ozscripts', 'oz_lang', $lan );
 }
 $clientsNF = json_encode($cliNotFormat,JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT);
 update_post_meta($post,'oz_clientsarray',$clientsNF);
}


add_action( 'admin_footer', 'book_oz_custom_dashboard_widget' );
function book_oz_custom_dashboard_widget() {
	// Bail if not viewing the main dashboard page
	if ( get_current_screen()->base !== 'dashboard' || (get_current_screen()->base == 'dashboard' && !current_user_can('edit_pages')) ) {
		return;
	}
	$ru = (get_locale() == 'ru_RU') ? '-ru' : '';
	$img = 'images/calendar-banner'.$ru.'.jpg';
	if (!file_exists(plugin_dir_path( __FILE__ ).$img)) return;
	?>

	<div id="oz_dashboard_widget_full" class="welcome-panel" style="display: none;">
		<div class="welcome-panel-content">
			<a target="_blank" href="http://demo.oz-plugin.ru?utm_source=sitebanner" class="oz_ext_banner"><img style="max-width:100%" src="<?php echo plugins_url('/'.$img, __FILE__); ?>?ver=<?php echo BOOKOZ_VER; ?>" /></a>
			<h2><?php _e('Booking calendar', 'book-appointment-online'); ?></h2><br>
			<p class="about-description"></p>
			<div class="welcome-panel-column-container">
				<div class="oz_widget_calendar"></div>
			</div>
		</div>
	</div>
	<?php
$prevDate = date('Y-m-d', strtotime("-30 days"));
$futDate = date('Y-m-d', strtotime("+30 days"));
//echo get_post_meta(1129,'oz_start_date_field_id', true);
echo $prevDate.' - '.$futDate;
	$args = array( 
	'posts_per_page' => -1, 
	'post_type' => 'clients',
	);
	$clients = get_posts( $args );
	foreach ( $clients as $client ) : setup_postdata( $client );
	$id = $client->ID;
	$usId = get_post_meta($id,'oz_uslug_set',true);
	$start = date('c',strtotime(get_post_meta($id,'oz_start_date_field_id', true).get_post_meta($id,'oz_time_rot', true)));
	$w_time = get_post_meta($usId,'oz_serv_time',true);
	$end = date('c',strtotime($start.'+'.$w_time.' minutes'));
	$tel = get_post_meta($id,'oz_clientPhone',true);
	$usl = get_the_title($usId);
	$pers_id = get_post_meta($id,'oz_personal_field_id',true);
	$pers = get_the_title($pers_id);
	$url = site_url().'/wp-admin/post.php?post='.$id.'&action=edit';
	$clientsJSON[] = array(
	'title' 	=> $client->post_title ,
	'start' 	=> $start,
	'end'		=> $end,
	'w_time' 	=> $w_time,
	'tel' 		=> $tel,
	'usl' 		=> $usl,
	'pers'		=> $pers,
	'url'		=> $url,
	);
endforeach;
echo '<script>clients = '.json_encode($clientsJSON).'</script>';
if (get_locale() == 'ru_RU' ) {
	$lan = 'ru';
}
 ?>
	<script>
		jQuery(document).ready(function($) {
			$('#wpbody-content h1').after($('#oz_dashboard_widget_full').show());
			var re = 'ext_banner';
			if (!jQuery('.oz_'+re).length) return;
			$('.oz_widget_calendar').fullCalendar({
				header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,listWeek,agendaDay'
				},
				<?php if (isset($lan)) : ?>locale: '<?php echo $lan; ?>',<?php endif; ?>
				defaultView: 'listWeek',
				editable: false,
				events: clients, 
				eventRender: function(event, element) {
						$('<div class="fc-ther_info"> '+event.tel+', '+event.usl+', '+event.pers+'</div>').insertAfter($(element).find('.fc-title, .fc-list-item-title a'));
					},
			});
		});
	</script>

<?php }
// ver 1.22
add_action('register_book_oz_settings_advanced', 'book_oz_time_duration_option');
function book_oz_time_duration_option() {
	register_setting('book_oz_settings', 'oz_time_duration');
}

add_action('book_oz_add_main_options','book_oz_addTimeDuration',11);
function book_oz_addTimeDuration() {
	$minutes = array(15,30,60,120);
	$time = get_option('oz_time_duration');
	?>
		<tr>
			<th scope="row"><label for="oz_time_format"><?php _e('Time slot duration', 'book-appointment-online'); ?></label></th>
			<td>
				<select id="oz_time_duration" name="oz_time_duration">
				<?php foreach ($minutes as $min) : ?>
					<option value="<?php echo $min; ?>" <?php selected( $time, $min ); ?>><?php echo $min; ?> <?php _e('minutes', 'book-appointment-online'); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
<?php }
add_filter('book_custFront_JSOptions', 'book_oz_add_duration');
add_filter('book_custAdmin_JSOptions', 'book_oz_add_duration');
function book_oz_add_duration($vars) {
	if (get_option('oz_time_duration')) {
		$vars['timeslot'] = get_option('oz_time_duration');
	}
	return $vars;
}

add_action('register_book_oz_settings_advanced', 'book_oz_autoheight_option');
function book_oz_autoheight_option() {
	register_setting('book_oz_settings', 'oz_autoheight');
}

add_action('book_oz_add_main_options','book_oz_autoheight',11);
function book_oz_autoheight() {
	$time = get_option('oz_autoheight');
	?>
		<tr>
			<th scope="row"><label for="oz_time_format"><?php _e('Autoheight', 'book-appointment-online'); ?></label></th>
			<td>
			<input type="checkbox" name="oz_autoheight" value="1"<?php checked(get_option('oz_autoheight') == 1);  ?>>
			</td>
		</tr>
<?php }
add_filter('book_custFront_JSOptions', 'book_oz_add_autoheight');
function book_oz_add_autoheight($vars) {
	if (get_option('oz_autoheight')) {
		$vars['autoheight'] = get_option('oz_autoheight');
	}
	$vars['dateFormat'] = convertPHPToMomentFormat(get_option('date_format')); 
	$vars['timeFormat'] = convertPHPToMomentFormat(get_option('time_format')); 
	$vars['lang'] = get_locale(); 
	$vars['redirect_url'] = get_option('oz_redirect_url'); 
	return $vars;
}

function convertPHPToMomentFormat($format)
{
    $replacements = [
        'd' => 'DD',
        'D' => 'ddd',
        'j' => 'D',
        'l' => 'dddd',
        'N' => 'E',
        'S' => 'o',
        'w' => 'e',
        'z' => 'DDD',
        'W' => 'W',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '', // no equivalent
        'L' => '', // no equivalent
        'o' => 'YYYY',
        'Y' => 'YYYY',
        'y' => 'YY',
        'a' => 'a',
        'A' => 'A',
        'B' => '', // no equivalent
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => 'SSS',
        'e' => 'zz', // deprecated since version 1.6.0 of moment.js
        'I' => '', // no equivalent
        'O' => '', // no equivalent
        'P' => '', // no equivalent
        'T' => '', // no equivalent
        'Z' => '', // no equivalent
        'c' => '', // no equivalent
        'r' => '', // no equivalent
        'U' => 'X',
    ];
    $momentFormat = strtr($format, $replacements);
    return $momentFormat;
}

add_action('book_oz_add_main_options', 'book_oz_colors');

/**
 *  Colors options
 *  
 *  @return void
 *  
 *  @version 2.1.1
 */
function book_oz_colors() {
	$colors = get_option('oz_colors');
	if (!wp_script_is( 'colorpicker', 'registered' )) {
	echo '<tr><th><strong>'.__('Something is wrong with color settings', 'book-appointment-online').'</strong></th></tr>'; }
	else {
		?>
		<script>
		jQuery(document).ready(function() {
			if (jQuery('.oz_colors').length > 0) {
				jQuery('.oz_colors').wpColorPicker();
			}
		})
		</script>
		<?php
	}
?>
	<tr>
		<th scope="row"><label><?php _e('Color options', 'book-appointment-online'); ?></label>
		<br>
		<small><?php _e('Main color, second color, background color', 'book-appointment-online'); ?></small>
		</th>
		<td>
			<input name="oz_colors[primary]" type="text" id="oz_colors_primary" value="<?php echo isset($colors['primary']) ? $colors['primary'] : ''; ?>" class="oz_colors">
			<input name="oz_colors[second]" type="text" id="oz_colors_second" value="<?php echo isset($colors['second']) ? $colors['second'] : ''; ?>" class="oz_colors">
			<input name="oz_colors[background]" type="text" id="oz_colors_background" value="<?php echo isset($colors['background']) ? $colors['background'] : ''; ?>" class="oz_colors">
		</td>
	</tr>
<?php
}

add_action( 'admin_enqueue_scripts', 'book_oz_add_color_picker' );
function book_oz_add_color_picker() {
	$current = get_current_screen();
	if ($current->base == 'toplevel_page_book-appointment-online/book-appointment-online' && !wp_script_is( 'wp-color-picker', 'enqueued' ))
	wp_enqueue_script( 'wp-color-picker');
}

add_action('book_oz_before_step_title', 'book_oz_colors_styles');

/**
 *  Color CSS on frontend
 *  
 *  @return void
 *  
 *  @version 2.1.1
 */
function book_oz_colors_styles() {
	$colors = get_option('oz_colors');
	if (!$colors) return;
	$primaryColors = array(
	'background-color' => array(
	'.oz_btn:not(.oz_usl_btn)',
	'.oz_hid input[type="submit"]',
	'.oz_btn.oz_spec_btn',
	'.oz_hid .ui-datepicker-calendar tbody td[data-handler="selectDay"] a:after',
	'.oz_hid  .ui-datepicker-header',
	'.oz_hid_carousel ul li.squaredThree label:hover',
	'.oz_zapis_info',
	'.oz_back_btn',
	'.oz_future:before',
	'.oz_pop_btn'
	),
	'color' => array(
	'.tz_colors',
	'.tz_stop',
	'.oz_hid .ui-datepicker-title',
	//'.oz_hid .ui-datepicker-calendar tbody td[data-handler="selectDay"] a'
	),
	'border-top-color' => array(
	'.oz_hid_carousel ul li', //#2dde98
	'.oz_future:before',
	'body .oz_hid_carousel ul.oz_select .oz_li_sub ul'
	),
	'stroke' => array(
	'#oz_ok_icon path',
	'.checkmark__check',
	'.checkmark__circle'
	)
	);
	$primaryColors = apply_filters('book_oz_primaryColors', $primaryColors);
	
	$secondColors = array(
	'background-color' => array(
	'.oz_btn.oz_spec_btn:hover',
	'.oz_btn:not(.oz_usl_btn):hover',
	'.oz_hid input[type="submit"]:hover',
	'.oz_btn.oz_spec_btn:active',
	'.oz_btn:not(.oz_usl_btn):active',
	'.oz_hid input[type="submit"]:active',
	'.oz_btn.oz_spec_btn:focus',
	'.oz_btn:not(.oz_usl_btn):focus',
	'.oz_hid input[type="submit"]:focus',
	'.oz_back_btn:hover'
	)
	);
	$secondColors = apply_filters('book_oz_secondColors', $secondColors);
	
	$backColors = array(
	'background-color' => array(
	'.oz_container',
	'.oz_popup'
	)
	);
	$backColors = apply_filters('book_oz_backColors', $backColors);
	if ((isset($colors['primary']) && $colors['primary']) || (isset($colors['second']) && $colors['second']) || (isset($colors['background']) && $colors['background'])) {
	?>
	<style>
	<?php 
		  foreach ($primaryColors as $key => $primaryColor) {
			  $classes = implode(',',$primaryColors[$key]);
			  echo $classes.' {'.$key.':'.$colors['primary'].' !important}';
		  }
		  //#3cffb2
		  foreach ($secondColors as $key => $secondColor) {
			  $classes = implode(',',$secondColors[$key]);
			  echo $classes.' {'.$key.':'.$colors['second'].' !important}';
		  }
		  
		  foreach ($backColors as $key => $backColor) {
			  $classes = implode(',',$backColors[$key]);
			  echo $classes.' {'.$key.':'.$colors['background'].' !important}';
		  }
	?>
	</style>
	<?php
	}
}

add_action('admin_footer', 'book_oz_why_deactivate');


add_action( 'admin_enqueue_scripts', 'book_oz_add_wp_pointer', 99 );

function book_oz_add_wp_pointer() {
    wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );
}

function book_oz_why_deactivate() {
	$current = get_current_screen();
	if ($current && $current->base == 'plugins') :
	$par =[
		'adminAjax' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ozajax-nonce'),
		];
?>
<style>
	#book_oz_better {
		display:flex;
		flex-direction: column;
		padding:0 20px;
	}
	
	#book_oz_better label {
		margin-bottom:10px;
	}
	.oz_reason_choose {
		padding: 0 !important;
		color: red;
		margin: 0 0 10px;
	}
	
	.book_oz_better_wrapper.oz_load {
		opacity:0.6;
	}
</style>
    <script type="text/javascript">
	jQuery(document).ready(function($) {
		let dismissed = false
			$('#deactivate-book-appointment-online').click(function(e) {
				if (dismissed) return;
				e.preventDefault()
				$(this).pointer({
								content: '<h3><?php _e('Please tell us why are you deactivating the plugin?', 'book-appointment-online'); ?></h3>'+
								'<p><?php _e('Please tell us why are you deactivating the plugin?', 'book-appointment-online'); ?></p>'+
								'<div class="book_oz_better_wrapper"><form id="book_oz_better">'+
									'<label><input name="reason" type="checkbox" value="Poor design" /> <?php _e('Poor design', 'book-appointment-online'); ?></label>'+
									'<label><input name="reason" type="checkbox" value="Too few features" /> <?php _e('Too few features', 'book-appointment-online'); ?></label>'+
									'<label><input name="reason" type="checkbox" value="Plugin is buggy on my website" /> <?php _e('Plugin is buggy on my website', 'book-appointment-online'); ?></label>'+
									'<label><input name="reason" type="checkbox" value="This is a temporary deactivation" /> <?php _e('This is a temporary deactivation', 'book-appointment-online'); ?></label>'+
									'<input style="margin: 5px 0 15px;" type="text" name="another_reason" placeholder="<?php _e('Another reason', 'book-appointment-online'); ?>" />'+
									'<input type="submit" style="align-self: flex-start;" class="button" value="<?php _e('Submit', 'book-appointment-online'); ?>">'+
								'</form></div>',
								position: 'top',
								close: function() {
									dismissed = true
								}
							}).pointer('open');
			})
			
			$('body').on('submit', '#book_oz_better', async function(e) {
					e.preventDefault();
					let vals = {
					reason: [],
					another_reason: ''
					}

					$(this).serializeArray().forEach(input => { 
						if (input.name == 'reason') {
							vals.reason.push(input.value)
						}
						else if (input.name == 'another_reason') {
							vals.another_reason = input.value
						}
					})
					$('.book_oz_better_wrapper').addClass('oz_load')
					if (vals.reason.length || vals.another_reason.length > 3) {
						$('.oz_reason_choose').remove()
						try {
						let body = new URLSearchParams();
						body.set('action', 'oz_reason')
						body.set('form', JSON.stringify(vals))
						body.set('_ajax_nonce', '<?php echo $par['nonce']; ?>')
						let res = await (await fetch('<?php echo $par['adminAjax']; ?>', {
							method: 'post',
							body
						})).json()
						$('.book_oz_better_wrapper').removeClass('oz_load').html('<p><?php _e('Deactivating the plugin...', 'book-appointment-online'); ?></p>')
						dismissed = true
						}
					catch(err) {
						console.log(err)
					}
						window.location.href = $('#deactivate-book-appointment-online').attr('href')
												
					}
					else {
						$('<p class="oz_reason_choose"><?php _e('Please choose or typing a reason', 'book-appointment-online'); ?></p>').insertBefore($(this).find('input[type="submit"]'))
						setTimeout(() => {
							$('.oz_reason_choose').remove()
						}, 20000)
					}
					$('.book_oz_better_wrapper').removeClass('oz_load')
			})
	})
    </script><?php
	endif;
}

add_action('wp_ajax_oz_reason', 'oz_reason_func');

function oz_reason_func() {
	if (wp_doing_ajax() && check_ajax_referer('ozajax-nonce')) {
		$form = json_decode(stripslashes($_POST['form']),1);
		$query = http_build_query($form);
		$res = wp_safe_remote_get('http://oz-plugin.ru/?deactivation_reason=1&'.$query);
		if (!is_wp_error($res)) {
			//wp_remote_retrieve_body($res)
		}
		echo json_encode([
			'success' => true,
			'payload' => wp_remote_retrieve_body($res)
		]);
	}
	wp_die();
}

 ?>