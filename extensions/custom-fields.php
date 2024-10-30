<?php
/**
 * @author    Ozplugin <client@oz-plugin.ru>
 * @link      http://www.oz-plugin.ru/
 * @copyright 2018 Ozplugin
 * @ver 1.30
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class BAO_CF {
	
	function __construct() {
	add_filter('book_oz_tabs_line', array($this,'tab'),8,1);
	add_action('book_oz_advanced_tabs', array($this,'tab_content'),10,2);
	}
	
		/* добавляем вкладку для формы */
	public function tab($tab) {
		$tab[] = array('id' => 'form-li', 'name' => __('Form settings', 'book-appointment-online'), 'tabIcon' => 'dashicons-schedule' );
		return $tab;
	}

	/* добавляем вкладку полей*/
	public function tab_content($oz_theme, $oz_vid) {
	?>
	<div id="form-li-tab" class="book_oz_tab">
		<table  class="form-table">
			<tbody>
	<?php 
	$oz_polya = get_option( 'oz_polya' );
	$tel = (isset($oz_polya['tel']['name'])) ? $oz_polya['tel']['name'] : 0;
	$reqtel = (isset($oz_polya['tel']['req'])) ? $oz_polya['tel']['req'] : 0;
	$email = (isset($oz_polya['email']['name'])) ? $oz_polya['email']['name'] : 0;
	$reqemail = (isset($oz_polya['email']['req'])) ? $oz_polya['email']['req'] : 0;
		?>
		<tr>
			<th scope="row"><?php _e('Form fields', 'book-appointment-online'); ?></th>
				<td>
					<fieldset><legend><strong><?php _e('Name', 'book-appointment-online'); ?></strong></legend>
						<label for="oz_polya_name">
							<input type="checkbox" checked disabled name="oz_polya[name][name]" />
							<?php _e('Yes', 'book-appointment-online'); ?>
							<input type="checkbox" checked disabled name="oz_polya[name][req]" />
							<?php _e('Required', 'book-appointment-online'); ?>
						</label>
					</fieldset>
					<br>
					<fieldset><legend><strong><?php _e('Phone', 'book-appointment-online'); ?></strong></legend>
						<label for="oz_polya_tel">
							<input type="checkbox" name="oz_polya[tel][name]" value="1"<?php checked( 1 == $tel ); ?> />
							<?php _e('Yes', 'book-appointment-online'); ?>
							<input type="checkbox" name="oz_polya[tel][req]" value="1"<?php checked( 1 == $reqtel ); ?> />
							<?php _e('Required', 'book-appointment-online'); ?>
						</label>
					</fieldset>
					<br>
					<fieldset><legend><strong><?php _e('Email', 'book-appointment-online'); ?></strong></legend>
						<label for="oz_polya_email">
							<input type="checkbox" name="oz_polya[email][name]" value="1"<?php checked( 1 == $email ); ?> />
							<?php _e('Yes', 'book-appointment-online'); ?>
							<input type="checkbox" name="oz_polya[email][req]" value="1"<?php checked( 1 == $reqemail ); ?> />
							<?php _e('Required', 'book-appointment-online'); ?>
						</label>
					</fieldset>
			</td>
			</tr>
			<?php if(!isset($_COOKIE) || !isset($_COOKIE['oz_hide']) || $_COOKIE['oz_hide'] != 'y') : ?>
<tr>
						<th scope="row">
							<label>
								Custom form field <sup style="position: initial;">Only in PRO</sup>							</label>
						</th>
						<td>
							<div class="oz_cust_fields_wrap">
								<div class="oz_cust_fields ui-sortable">
											<div data-line="0" class="oz_cust_field_line">
			<div class="oz_cust_elem oz_cust_sorting ui-sortable-handle">
				<legend><strong>Order</strong></legend>
				<span class="order-number">1</span>
				<input type="hidden" name="oz_cust_fields[0][order]" value="0">
			</div>
			<div class="oz_cust_elem oz_cust_field_name">
				<legend><strong>Custom field name</strong></legend>
				<input name="" disabled type="text" id="oz_cust_field_name_0" value="" class="regular-text">
			</div>
			<div class="oz_cust_elem oz_cust_field_type">
			<legend><strong>Select field type</strong></legend>
				<select name="" disabled>
					<option selected="selected" value="input">Input</option>
					<option value="textarea">Textarea</option>
					<option value="select">Select</option>
					<option value="checkbox">Checkbox</option>
				</select>
				<legend class="oz_cust_field_textarea hide">
				<strong>Choices</strong><br>
					<small>
					Enter each choice on a new line					</small>
				<textarea name="oz_cust_fields[0][values]" rows="5"></textarea>
				</legend>
			</div>
			<div class="oz_cust_elem oz_cust_field_req">
				<legend><strong>Required</strong></legend>
				<input type="checkbox" id="oz_cust_field_req0" name="" disabled value="1">
			</div>
			<div class="oz_cust_elem oz_cust_field_remove">
				<span class="dashicons dashicons-no-alt"></span>
			</div>
			</div>
						<div data-line="1" class="oz_cust_field_line">
			<div class="oz_cust_elem oz_cust_sorting ui-sortable-handle">
				<legend><strong>Order</strong></legend>
				<span class="order-number">2</span>
			</div>
			<div class="oz_cust_elem oz_cust_field_name">
				<legend><strong>Custom field name</strong></legend>
				<input name="" disabled type="text" id="oz_cust_field_name_1" value="" class="regular-text">
			</div>
			<div class="oz_cust_elem oz_cust_field_type">
			<legend><strong>Select field type</strong></legend>
				<select name="" disabled>
					<option value="input">Input</option>
					<option selected="selected" value="textarea">Textarea</option>
					<option value="select">Select</option>
					<option value="checkbox">Checkbox</option>
				</select>
				<legend class="oz_cust_field_textarea hide">
				<strong>Choices</strong><br>
					<small>
					Enter each choice on a new line					</small>
				<textarea name="" rows="5"></textarea>
				</legend>
			</div>
			<div class="oz_cust_elem oz_cust_field_req">
				<legend><strong>Required</strong></legend>
				<input type="checkbox" id="oz_cust_field_req1" name="" disabled value="">
			</div>
			<div class="oz_cust_elem oz_cust_field_remove">
				<span class="dashicons dashicons-no-alt"></span>
			</div>
			</div>
						<div data-line="2" class="oz_cust_field_line">
			<div class="oz_cust_elem oz_cust_sorting ui-sortable-handle">
				<legend><strong>Order</strong></legend>
				<span class="order-number">3</span>
			</div>
			<div class="oz_cust_elem oz_cust_field_name">
				<legend><strong>Custom field name</strong></legend>
				<input name="" disabled type="text" id="oz_cust_field_name_2" value="" class="regular-text">
			</div>
			<div class="oz_cust_elem oz_cust_field_type">
			<legend><strong>Select field type</strong></legend>
				<select name="" disabled>
					<option value="input">Input</option>
					<option value="textarea">Textarea</option>
					<option selected="selected" value="select">Select</option>
					<option value="checkbox">Checkbox</option>
				</select>
				<legend class="oz_cust_field_textarea ">
				<strong>Choices</strong><br>
					<small>
					Enter each choice on a new line					</small>
				<textarea name="" disabled rows="5"></textarea>
				</legend>
			</div>
			<div class="oz_cust_elem oz_cust_field_req">
				<legend><strong>Required</strong></legend>
				<input type="checkbox" id="oz_cust_field_req2" name="" disabled value="">
			</div>
			<div class="oz_cust_elem oz_cust_field_remove">
				<span class="dashicons dashicons-no-alt"></span>
			</div>
			</div>
						<div data-line="3" class="oz_cust_field_line">
			<div class="oz_cust_elem oz_cust_sorting ui-sortable-handle">
				<legend><strong>Order</strong></legend>
				<span class="order-number">4</span>
			</div>
			<div class="oz_cust_elem oz_cust_field_name">
				<legend><strong>Custom field name</strong></legend>
				<input name="" disabled type="text" id="oz_cust_field_name_3" value="" class="regular-text">
			</div>
			<div class="oz_cust_elem oz_cust_field_type">
			<legend><strong>Select field type</strong></legend>
				<select name="" disabled>
					<option value="input">Input</option>
					<option value="textarea">Textarea</option>
					<option value="select">Select</option>
					<option selected="selected" value="checkbox">Checkbox</option>
				</select>
				<legend class="oz_cust_field_textarea ">
				<strong>Choices</strong><br>
					<small>
					Enter each choice on a new line					</small>
				<textarea name="" disabled rows="5"></textarea>
				</legend>
			</div>
			<div class="oz_cust_elem oz_cust_field_req">
				<legend><strong>Required</strong></legend>
				<input type="checkbox" id="oz_cust_field_req3" name="" disabled value="">
			</div>
			<div class="oz_cust_elem oz_cust_field_remove">
				<span class="dashicons dashicons-no-alt"></span>
			</div>
			</div>
											</div>
								<div class="oz_set_add_field button">Add field</div>
							</div>
						</td>
					</tr>
					<?php endif; ?>
			<tbody>
		</table>
	</div>
			<?php
	}
}

new BAO_CF();