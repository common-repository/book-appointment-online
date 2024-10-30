<?php
/**
 * @author    Ozplugin <client@oz-plugin.ru>
 * @link      http://www.oz-plugin.ru/
 * @copyright 2018 Ozplugin
 * @ver 1.37
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

ob_start();
?>
	<tr class="only-pro-tr">
		<td colspan="2">
			<div class="only-pro-div">
				This features available only in PRO Version <a href="https://demo.oz-plugin.ru/?utm_source=see_features" target="_blank" class="neum-btn"> Go to PRO</a> <span>No, thanks</span>
			</div>
		</td>
	</tr>
<?php $onlypro = ob_get_clean(); ?>
<div id="appointment-li-tab" class="book_oz_tab">
			<table class="form-table">
				<tbody>
						<?php echo $onlypro; ?>
						<tr>
		<th scope="row"><label for="oz_multiselect_serv">Multiselect for services</label></th>
		<td>
			<input type="checkbox" name="" disabled value="">
		</td>
	</tr>
		<tr>
			<th scope="row"><label for="oz_month_max_show">Max month for calendar display on front (from now)</label></th>
			<td>
				<select id="oz_month_max_show" disabled name="">
									<option value="1" >1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option selected="selected" value="5">5</option>
										<option value="6">6</option>
									</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="oz_time_min_show">Min time for booking (from now)</label></th>
			<td>
				<select id="oz_time_min_show" disabled name="">
									<option value="0" selected="selected">None</option>
										<option value="1">1 h</option>
										<option value="3">3 h</option>
										<option value="6">6 h</option>
										<option value="12">12 h</option>
										<option value="24">1 d</option>
										<option value="48">2 d</option>
										<option value="72">3 d</option>
										<option value="168">7 d</option>
										<option value="336">14 d</option>
									</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="oz_time_min_cancel">Min time for canceling booking (from now and only for logged in users)</label></th>
			<td>
				<select id="oz_time_min_cancel" disabled name="">
									<option value="0" selected="selected">None</option>
										<option value="1">1 h</option>
										<option value="3">3 h</option>
										<option value="6">6 h</option>
										<option value="12">12 h</option>
										<option value="24">1 d</option>
										<option value="48">2 d</option>
										<option value="72">3 d</option>
										<option value="168">7 d</option>
										<option value="336">14 d</option>
									</select>
			</td>
		</tr>
					<tr>
						<th scope="row"><label for="book_oz_enable_statuses">Аppointment statuses</label></th>
						<td>
							<input type="checkbox" disabled name="" class="oz_has_sub_options" checked="checked" value="1">
						</td>
					</tr>
					<tr data-show="book_oz_enable_statuses" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">Status by default</label><br>
						<small>Appointments with status 'Approved' and 'On hold' will block the selected employee time slot</small>
						</th>
						<td>
						<select id="oz_status_def" disabled name="">
														<option value="approved" selected="selected">Approved</option>
														<option value="onhold">On hold</option>
														<option value="canceled">Canceled</option>
													</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="oz_conf_pageid">Page ID for conference</label><br>
							<small>Leave this field blank if you do not have remote services.</small><br>
							<small>Beta release client-to-client video conferencing. Should work only in modern browsers. You must have active support to use it</small>
						</th>
						<td style="vertical-align:top;">
							<input name="" disabled type="number" id="oz_conf_pageid" value="">
						</td>
					</tr>
						<tr>
		<th scope="row">
			<label for="book_oz_skip_personal">How will the booking start</label><br>
		</th>
		<td style="vertical-align:top;">
			<select id="book_oz_skip_personal" disabled name="">
				<option value="" selected="selected">Default - Start with staff/branches. Skip employee option is available</option>
				<option value="1">Start with services. Booking will start with services</option>
				<option value="staff">Start with staff/branches. Skip employee option is unavailable</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="oz_skip_step_ifOne">Skip step if one employee/service</label><br>
		</th>
		<td style="vertical-align:top;">
			<input name="" disabled type="checkbox" id="oz_skip_step_ifOne" value="1">
		</td>
	</tr>
					
					<tr>
						<th scope="row"><label for="oz_status_def">Enable Push notification (Firebase)</label><br>
						<small>This technology is supported only by some browsers. Firebase registration required</small>
						</th>
						<td>
							<input type="checkbox" disabled class="oz_has_sub_options" checked="checked" name="" value="1">
						</td>
					</tr>
										<tr data-show="oz_push_settings[enable]" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">API Key</label><br>
						<small>Find it in your <a href="https://console.firebase.google.com" target="_blank">Firebase console</a></small>
						</th>
						<td>
							<input name="" disabled type="text" id="oz_push_settings[api]" value="" class="oz_push_settings">
						</td>
					</tr>
					<tr data-show="oz_push_settings[enable]" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">Server Key (for Cloud Messaging)</label><br>
						<small>Find it in your <a href="https://console.firebase.google.com" target="_blank">Firebase console</a></small>
						</th>
						<td>
							<input name="" disabled type="text" id="oz_push_settings[serverkey]" value="" class="oz_push_settings">
						</td>
					</tr>
					<tr data-show="oz_push_settings[enable]" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">Project ID</label><br>
						<small>Find it in your <a href="https://console.firebase.google.com" target="_blank">Firebase console</a></small>
						</th>
						<td>
							<input name="" disabled type="text" id="oz_push_settings[projectId]" value="" class="oz_push_settings">
						</td>
					</tr>
					<tr data-show="oz_push_settings[enable]" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">Messaging Sender ID</label><br>
						<small>Find it in your <a href="https://console.firebase.google.com" target="_blank">Firebase console</a></small>
						</th>
						<td>
							<input name="" disabled type="text" id="oz_push_settings[messagingSenderId]" value="" class="oz_push_settings">
						</td>
					</tr>
										<tr data-show="oz_push_settings[enable]" class="oz_sub_option">
						<th scope="row"><label for="oz_status_def">Add your device</label><br>
						<small>Appointments with status 'Approved' and 'On hold' will block the selected employee time slot</small>
						</th>
						<td id="oz_firebase_events">
														First specify the Firebase settings. All fields are required. Then save the settings and add your device.													</td>
					</tr>
				</tbody>
			</table>
		</div>
<div id="payment-li-tab" class="book_oz_tab">
	<input type="hidden" class="chist" name="" disabled="disabled" checked="checked" value="">
		<table class="form-table">
			<tbody>
			<?php echo $onlypro; ?>
				<tr>
					<th scope="row">
						<label>
							Enable						</label>
						<small></small>
					</th>
					<td>
						<input type="checkbox" id="oz_payment" disabled="disabled" checked="checked" name="" value="1">
					</td>
				</tr>
				<tr scope="row">
					<th scope="row"><label for="oz_payment_locally">Locally</label><br>
					<small>Сan be disabled if something other payment methods are active</small>
					</th>
					<td>
						<input name="" id="oz_payment_locally" disabled="disabled" type="checkbox" value="1" checked="checked">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label>
							Payment method - PayPal<br>
							<small>You can accept payments via PayPal. Important! The plugin does not show the payment status of appointments. Check payment status in your Paypal account</small>
						</label>
					</th>
					<td>
						<label for="oz_polya_name">
							<input type="checkbox" disabled="disabled" checked="checked" class="set_payment" name="">
							Paypal						</label>
					</td>
				</tr>
				<tr class="show_if_paypal">
					<th scope="row"><label for="oz_paypal_account">Paypal account</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_paypal_account" value="" class="regular-text">
						<label for="oz_paypal_sandbox" style="padding-top:5px;">
							<input type="checkbox" name="" disabled="disabled" value="1">
							Enable Paypal Sandbox						</label>
					</td>
				</tr>
				<tr class="show_if_paypal">
					<th scope="row">
					<label for="oz_paypal_return_url">Paypal return URL</label><br>
					<small>Optional. Link to the page to which the user will return in case of successful payment. For example: <?php echo site_url(); ?>/return_page/</small>
					</th>
					<td>
						<input name="oz_paypal_return_url" disabled="disabled" type="text" id="oz_paypal_return_url" value="" class="regular-text">
					</td>
				</tr>
				<tr class="show_if_paypal">
					<th scope="row">
						<label for="oz_paypal_cancel_url">Paypal cancel URL</label><br>
						<small>Optional. Link to the page to which the user will return in case of cancel/error payment. For example: <?php echo site_url(); ?>/cancel_page/</small>
					</th>
					<td>
						<input name="oz_paypal_cancel_url" disabled="disabled" type="text" id="oz_paypal_cancel_url" value="" class="regular-text">
					</td>
				</tr>
				<tr class="show_if_paypal">
					<th scope="row"><label for="oz_paypal_cancel_url">Paypal currency</label></th>
					<td>
								<select name="oz_paypal_currency" disabled="disabled" value="AUD">
									<option value="AUD" selected="selected">AUD - Australian Dollar</option>
									<option value="BRL">BRL - Brazilian Real</option>
									<option value="CAD">CAD - Canadian Dollar</option>
									<option value="CZK">CZK - Czech Koruna</option>
									<option value="DKK">DKK - Danish Krone</option>
									<option value="EUR">EUR - Euro</option>
									<option value="HKD">HKD - Hong Kong Dollar</option>
									<option value="HUF">HUF - Hungarian Forint</option>
									<option value="ILS">ILS - Israeli New Sheqel</option>
									<option value="JPY">JPY - Japanese Yen</option>
									<option value="MYR">MYR - Malaysian Ringgit</option>
									<option value="MXN">MXN - Mexican Peso</option>
									<option value="NOK">NOK - Norwegian Krone</option>
									<option value="NZD">NZD - New Zealand Dollar</option>
									<option value="PHP">PHP - Philippine Peso</option>
									<option value="PLN">PLN - Polish Zloty</option>
									<option value="GBP">GBP - Pound Sterling</option>
									<option value="RUB">RUB - Russian Ruble</option>
									<option value="SGD">SGD - Singapore Dollar</option>
									<option value="SEK">SEK - Swedish Krona</option>
									<option value="CHF">CHF - Swiss Franc</option>
									<option value="TWD">TWD - Taiwan New Dollar</option>
									<option value="THB">THB - Thai Baht</option>
									<option value="TRY">TRY - Turkish Lira</option>
									<option value="USD">USD - U.S. Dollar</option>
								</select>
				</td></tr>
						<tr>
			<th scope="row">
				<label>
					Payment method - Stripe<br>
					<small>You can accept payments via Stripe. Important! The plugin does not show the payment status of appointments. Check payment status in your Stripe account</small>
				</label>
			</th>
			<td>
				<label for="oz_polya_name">
					<input type="checkbox" disabled="disabled" checked="checked" class="advanced_payments oz_has_sub_options" name="oz_advancedPayments[stripe][enable]" value="1">
					Stripe				</label>
			</td>
		</tr>
		<tr data-show="oz_advancedPayments[stripe][enable]" class="oz_sub_option">
			<th scope="row"><label for="oz_advancedPayments[stripe][publishable_key]">Publishable key</label><br>
			<small>Publishable key</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_advancedPayments[stripe][publishable_key]" value="" class="oz_advancedPayments_settings">
			</td>
		</tr>
		<tr data-show="oz_advancedPayments[stripe][enable]" class="oz_sub_option">
			<th scope="row"><label for="oz_advancedPayments[stripe][secret_key]">Secret key</label><br>
			<small>Secret key</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_advancedPayments[stripe][secret_key]" value="" class="oz_advancedPayments_settings">
			</td>
		</tr>
		<tr data-show="oz_advancedPayments[stripe][enable]" class="oz_sub_option">
			<th scope="row">
			<label for="oz_advancedPayments[stripe][success_url]">Stripe Success URL</label><br>
			<small>Required. Link to the page to which the user will return in case of successful payment. For example: <?php echo site_url(); ?>/return_page/</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_advancedPayments[stripe][success_url]" value="" class="regular-text">
			</td>
		</tr>
		<tr data-show="oz_advancedPayments[stripe][enable]" class="oz_sub_option">
			<th scope="row">
			<label for="oz_advancedPayments[stripe][cancel_url]">Stripe Cancel URL</label><br>
			<small>Required. Link to the page to which the user will return in case of cancel/error payment. For example: <?php echo site_url(); ?>/cancel_page/</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_advancedPayments[stripe][cancel_url]" value="" class="regular-text">
			</td>
		</tr>
		<tr data-show="oz_advancedPayments[stripe][currency]" class="oz_sub_option">
			<th scope="row"><label for="oz_advancedPayments[stripe][currency]">Stripe currency</label></th>
			<td>
										<select name="" disabled="disabled" value="AUD">
											<option value="AUD" selected="selected">AUD - Australian Dollar</option>
											<option value="BRL">BRL - Brazilian Real</option>
											<option value="GBP">GBP - British Pound</option>
											<option value="CAD">CAD - Canadian Dollar</option>
											<option value="CZK">CZK - Czech Koruna</option>
											<option value="DKK">DKK - Danish Krone</option>
											<option value="EUR">EUR - Euro</option>
											<option value="HKD">HKD - Hong Kong Dollar</option>
											<option value="HUF">HUF - Hungarian Forint</option>
											<option value="ILS">ILS - Israeli New Sheqel</option>
											<option value="JPY">JPY - Japanese Yen</option>
											<option value="MYR">MYR - Malaysian Ringgit</option>
											<option value="MXN">MXN - Mexican Peso</option>
											<option value="TWD">TWD - New Taiwan Dollar</option>
											<option value="NZD">NZD - New Zealand Dollar</option>
											<option value="NOK">NOK - Norwegian Krone</option>
											<option value="PHP">PHP - Philippine Peso</option>
											<option value="PLN">PLN - Polish Złoty</option>
											<option value="RUB">RUB - Russian Ruble</option>
											<option value="SGD">SGD - Singapore Dollar</option>
											<option value="SEK">SEK - Swedish Krona</option>
											<option value="CHF">CHF - Swiss Franc</option>
											<option value="THB">THB - Thai Baht</option>
											<option value="USD">USD - United States Dollar</option>
										</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>
					Payment method - Yandex Kassa<br>
					<small></small>
				</label>
			</th>
			<td>
				<label for="oz_polya_name">
					<input type="checkbox" class="advanced_payments oz_has_sub_options" name="" disabled value="">
					Yandex				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="oz_advancedPayments[yandex][shop_id]">Shop ID</label><br>
			<small>Shop ID</small>
			</th>
			<td>
				<input name="" disabled type="text" id="oz_advancedPayments[yandex][shop_id]" value="" class="oz_advancedPayments_settings">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="oz_advancedPayments[yandex][secret_key]">Secret key</label><br>
			<small>Secret key</small>
			</th>
			<td>
				<input name="" disabled type="text" id="oz_advancedPayments[yandex][secret_key]" value="" class="oz_advancedPayments_settings">
			</td>
		</tr>
		<tr >
			<th scope="row"><label for="oz_advancedPayments[yandex][currency]">Yandex kassa currency</label></th>
			<td>
										<select name="" disabled value="RUB">
											<option value="RUB" selected="selected">RUB - Russian Ruble</option>
											<option value="USD">USD - US Dollar</option>
											<option value="EUR">EUR - Euro</option>
											<option value="BYN">BYN - Belarusian Ruble</option>
											<option value="KZT">KZT - Tenge</option>
											<option value="CNY">CNY - Yuan Renminbi</option>
										</select>
			</td>
		</tr>
		<tr >
			<th scope="row"><label for="oz_advancedPayments[yandex][return_url]">Return URL</label><br>
			</th>
			<td>
				<input name="oz_advancedPayments[yandex][return_url]" type="text" id="oz_advancedPayments[yandex][return_url]" value="" class="oz_advancedPayments_settings">
			</td>
		</tr>
			<tr>
		<th scope="row"><label for="oz_discounts[enable]">Discounts</label>
		</th>
		<td>
			<input type="checkbox" name="" disabled="disabled" checked="checked" class="oz_has_sub_options" value="1">
		</td>
	</tr>
	<tr data-show="oz_discounts[enable]" class="oz_sub_option">
		<th scope="row"><label for="oz_discounts[sitekey]">Discount coupons</label><br>
		<small>Add coupons for discount</small>
		</th>
		<td>
			<div class="oz_multi_options oz_flex">
				<div class="oz_func_list_ oz_data_flex_1">
					<ul class="oz_func_list oz_coupons-list">
					No coupons found					</ul>
				</div>
				<div data-form="oz_coupons-edit" class="oz_edit_data oz_coupons-edit oz_data_flex_1">
				<input name="id" type="hidden" value="">
				<label for="">Add/edit coupon</label>
					<div class="oz_data_100 oz_row oz_mar_b_10">
						<div class="oz_data_flex_4">
								<input name="coupon" type="text" aria-required="true" placeholder="Coupon code" value="">
						</div>
						<div class="oz_data_flex_3">
								<input name="until" type="text" aria-required="true" class="oz_date hasDatepicker" data-params="{&quot;minDate&quot;:&quot;0&quot;, &quot;dateFormat&quot;:&quot;dd.mm.yy&quot;}" placeholder="Valid until" value="" id="dp1582892392364">
						</div>
					</div>
					<div class="oz_data_100  oz_row oz_mar_b_10">
						<div class="oz_data_flex_4">
							<div class="oz_data_100  oz_row">
								<div class="oz_data_flex_0 oz_nopadding_l">
									<input name="amount" aria-required="true" type="number" min="1" max="100" placeholder="Amount of discount" value="">
								</div>	
								<div class="oz_data_flex_0 oz_data_width_percent">
									<select name="type">
										<option value="0">%</option>
										<option value="1">Default currency</option>
									</select>
								</div>
							</div>
						</div>
						<div class="oz_data_flex_3">
								<input name="max" type="text" placeholder="Max discount (Default currency)" value="">
						</div>
					</div>
					<div class="oz_data_100  oz_row oz_mar_b_10">
						<div class="oz_data_flex_1">
							<label class="oz_label_block" for="">Services</label>
							<select id="oz_coupon_services" name="services" class="oz_select oz_select_ajax" multiple="" tabindex="-1" aria-hidden="true">
								<option selected="selected" value="0">All</option>
							</select>
						</div>
						<div class="oz_data_flex_1">
							<label class="oz_label_block" for="">Employees</label>
							<select id="oz_coupon_personal" name="personal" class="oz_select oz_select_ajax" multiple="" tabindex="-1" aria-hidden="true">
								<option selected="selected" value="0">All</option>
							</select>
						</div>
					</div>
					<div class="oz_data_100">
						<div class="oz_list_add button">Add</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
				</tbody>
		</table>
	</div>
<div id="sms-li-tab" class="book_oz_tab">
		<table class="form-table">
			<tbody>
			<?php echo $onlypro; ?>
				<tr>
					<th scope="row"><label>Enable SMS integration</label></th>
					<td>
						<input type="checkbox" id="oz_smsIntegration" disabled="disabled" name="" value="1">
					</td>
				</tr>
				<tr class="sms_Type">
					<th scope="row"><label for="oz_sms_serv_label">SMS service</label></th>
					<td>
					<select disabled="" id="oz_smsType" name="oz_smsType">
						<option value="as_Twilio">Twilio</option>
						<option value="as_smsc">SMSC (Russia)</option>
					</select>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table book_oz-form-sms form-sms-twilio">
			<tbody>
				<tr class="sms_Type__as_Twilio">
					<th class="th_zago" scope="row">Main settings Twilio</th><td></td>
				</tr>
				<tr class="sms_Type__as_Twilio">
					<th scope="row"><label>Twilio Account SID</label></th>
					<td>
						<input name="" type="text" id="oz_Twilio_sid" disabled="disabled" value="" class="regular-text">
						<small>Get it in your account at <a title="Twilio console" target="_blank" href="https://www.twilio.com/console">Twilio console</a></small>
					</td>
				</tr>
				<tr class="sms_Type__as_Twilio">
					<th scope="row"><label>Twilio Account Token</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_Twilio_token" value="" class="regular-text">
						<small>Get it in your account at <a title="Twilio console" target="_blank" href="https://www.twilio.com/console">Twilio console</a></small>
					</td>
				</tr>
				<tr class="sms_Type__as_Twilio">
					<th scope="row"><label>Twilio Account Phone Number or Alphanumeric Sender ID</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_Twilio_phoneNumber" value="" class="regular-text">
						<small>Get it in your account at <a title="Twilio console" target="_blank" href="https://www.twilio.com/console/phone-numbers/incoming">Twilio console</a></small><br>
					</td>
				</tr>
				<tr class="sms_Type__as_smsc">
					<th scope="row"><label>Smsc login</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_smsc_login" value="" class="regular-text">
						<small>Login of your account at <a title="SMSC" target="_blank" href="https://smsc.ru/?pp526774">SMSC</a></small><br>
					</td>
				</tr>
				<tr class="sms_Type__as_smsc">
					<th scope="row"><label>SMSC password</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_smsc_psw" value="" class="regular-text">
						<small>Password of your account at <a title="SMSC" target="_blank" href="https://smsc.ru/?pp526774">SMSC</a></small><br>
					</td>
				</tr>
				<tr class="sms_Type__as_smsc">
					<th scope="row"><label>SMSC sender (optional)</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_smsc_sender" value="" class="regular-text">
						<small>Sender ID <a title="SMSC" target="_blank" href="https://smsc.ru/senders/?pp526774">SMSC Senders</a></small><br>
					</td>
				</tr>
				<tr class="sms_Type__as_All">
					<th scope="row"><label>Admin Phone Number</label></th>
					<td>
						<input name="" disabled="disabled" type="text" id="oz_Admin_phoneNumber" value="" class="regular-text">
						<label for="oz_smsToAdmin"><input type="checkbox"  id="oz_smsToAdmin" name="" disabled="disabled" value="1"> Send SMS about booking to admin</label>
					</td>
				</tr>
				<tr class="sms_Type__as_All">
					<th scope="row"><label>Default text to admin</label></th>
					<td class="cols-flex">
						<div class="col-2">
							<textarea name="" disabled="disabled" id="oz_Twilio_user_textAdmin" col="60" rows="4" class="regular-text">New booking. Name: [book_oz_name], Phone:[book_oz_clientphone], Time booking: [book_oz_timebooking]. More information in site console</textarea><br>
						</div>
						<div class="col-2">
							<label>Shortcode values</label><br>
							<span class="oz_code">[book_oz_name]</span> - Client name<br>
							<span class="oz_code">[book_oz_timebooking]</span> - Time booking<br>
							<span class="oz_code">[book_oz_clientphone]</span> - Client phone						</div>
					</td>
				</tr>
				<tr class="sms_Type__as_All">
					<th scope="row"><label>Default text to user</label></th>
					<td class="cols-flex">
						<div class="col-2">
							<textarea name="" disabled="disabled" id="oz_Twilio_user_text" col="60" rows="4" class="regular-text">[book_oz_name], thank your for your booking. Time booking: [book_oz_timebooking]</textarea><br>
						</div>
						<div class="col-2">
							<label>Shortcode values</label><br>
							<span class="oz_code">[book_oz_name]</span> - Client name<br>
							<span class="oz_code">[book_oz_timebooking]</span> - Time booking						</div>
					</td>
				</tr>
				<tr class="sms_Type__as_All active">
					<th scope="row"><label>Default text-reminder</label></th>
					<td class="cols-flex">
						<div class="col-2">
							<textarea name="" disabled="disabled" id="oz_Twilio_user_textRemind" col="60" rows="4" class="regular-text">[book_oz_name],do not forget, your appointment will start in [book_oz_timesms] minutes</textarea><br>
						</div>
						<div class="col-2">
							<label>Shortcode values</label><br>
							<span class="oz_code">[book_oz_name]</span> - Client name<br>
							<span class="oz_code">[book_oz_timesms]</span> - Time-reminder						</div>
					</td>
				</tr>
				<tr class="sms_Type__as_All active">
									<th scope="row"><label>Default text on status change</label></th>
					<td class="cols-flex">
						<div class="col-2">
							<textarea name="" disabled="disabled" id="oz_sms_user_status_text" col="60" rows="4" class="regular-text">[book_oz_name],we changed the status of your appointment. New status: [book_oz_appointment_status]</textarea><br>
						</div>
						<div class="col-2">
							<label>Shortcode values</label><br>
							<span class="oz_code">[book_oz_name]</span> - Client name<br>
							<span class="oz_code">[book_oz_appointment_status]</span> - New status						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="integration-li-tab" class="book_oz_tab">
		<table class="form-table">
			<tbody>
			<?php echo $onlypro; ?>
					<tr>
			<th scope="row"><label for="book_oz_googleCalendar[enable]">Google Calendar</label>
				<br><small>Redirect URL: <?php echo site_url(); ?>/wp-admin/admin.php?page=book-appointment-online-pro/book-appointment-online-pro.php&amp;getToken=google</small>
			</th>
			<td>
				<input type="checkbox" disabled="disabled" name="" class="oz_has_sub_options" value="1">
			</td>
		</tr>
		<tr data-show="book_oz_googleCalendar[enable]" class="oz_sub_option">
			<th scope="row"><label for="book_oz_googleCalendar[clientEmail]">Client Email</label><br>
			<small>Your email address in google account</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="book_oz_googleCalendar[clientEmail]" value="" class="oz_googleCalendar_settings">
			</td>
		</tr>
		<tr data-show="book_oz_googleCalendar[enable]" class="oz_sub_option">
			<th scope="row"><label for="book_oz_googleCalendar[clientID]">Client ID</label><br>
			<small>Your Client ID in google account</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="book_oz_googleCalendar[clientID]" value="" class="oz_googleCalendar_settings">
			</td>
		</tr>
		<tr data-show="book_oz_googleCalendar[enable]" class="oz_sub_option">
			<th scope="row"><label for="book_oz_googleCalendar[clientSecret]">Client Secret</label><br>
			<small>Your Client Secret in google account</small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="book_oz_googleCalendar[clientSecret]" value="" class="oz_googleCalendar_settings">
			</td>
		</tr>
		<tr data-show="book_oz_googleCalendar[enable]" class="oz_sub_option">
			<th scope="row"><label for="oz_status_def">Authorization</label><br>
			<small>Your Client ID in google account</small>
			</th>
			<td>
						<a href="javascript:void(0)" class="button only-pro-opac">Authorize
					</a></td>
		</tr>
			<tr>
			<th scope="row"><label for="oz_recaptcha[enable]">Google reCAPTCHA v2 (checkbox)</label>
			</th>
			<td>
				<input type="checkbox" disabled="disabled" name="" class="oz_has_sub_options" value="1">
			</td>
		</tr>
		<tr data-show="oz_recaptcha[enable]" class="oz_sub_option">
			<th scope="row"><label for="oz_recaptcha[sitekey]">Site key</label><br>
			<small>Get it in your reCAPTCHA <a href="https://www.google.com/u/3/recaptcha/admin/">admin console</a></small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_recaptcha[sitekey]" value="" class="oz_recaptcha_settings">
			</td>
		</tr>
		<tr data-show="oz_recaptcha[enable]" class="oz_sub_option">
			<th scope="row"><label for="oz_recaptcha[secretkey]">Secret Key</label><br>
			<small>Get it in your reCAPTCHA <a href="https://www.google.com/u/3/recaptcha/admin/">admin console</a></small>
			</th>
			<td>
				<input name="" disabled="disabled" type="text" id="oz_recaptcha[secretkey]" value="" class="oz_recaptcha_settings">
			</td>
		</tr>
				</tbody>
		</table>
	</div>