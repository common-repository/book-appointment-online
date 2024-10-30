<?php 
/**
 * @author    Ozplugin <client@oz-plugin.ru>
 * @link      http://www.oz-plugin.ru/
 * @copyright 2018 Ozplugin
 * @ver 1.37
 * Email on send appointment request
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<html>
<head>
	<meta charset="utf-8"> <!-- utf-8 works for most cases -->
	<meta name="viewport" content="width=device-width,initial-scale=1"> <!-- Forcing initial-scale shouldn't be necessary -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
	<title></title>
    <style>
/* What it does: Remove spaces around the email design added by some email clients. */
		/* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
	        margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        /* What is does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }
        
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
                
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            Margin: 0 auto !important;
        }
        table table table {
            table-layout: auto; 
        }
        
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        
        /* What it does: A work-around for iOS meddling in triggered links. */
        .mobile-link--footer a,
        a[x-apple-data-detectors] {
            color:inherit !important;
            text-decoration: underline !important;
        }
		
 /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
			@media (max-width:480px) {
			
			.h3, h3 {
				font-size:24px !important;
			}
			
			.mob100 {
				width:100% !important;
				max-width:100% !important;
			}
			}

    </style>
</head>
<body bgcolor="#e2e0ec" width="100%" style="margin: 0; max-width:100%;">
    <center style="width: 100%; background: #e2e0ec;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="padding:5px 0;font-family: sans-serif; font-size: 14px;">
            <?php _e('(Optional) This demo Email template. You can add your own template.', 'book-appointment-online'); ?>
        </div>
        <!-- Visually Hidden Preheader Text : END -->
        
        <!-- Email Body : BEGIN -->
        <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="600" style="margin: auto; width:600px" class="email-container">
            
            <!-- Hero Image, Flush : BEGIN -->
            <tr>
				<td> 
					<div style="width:100%;height:200px;margin:0 auto;">  
						<div style="max-height:0;max-width:100%;width:600px;overflow: visible;">
							<div style="width:600px;height:200px;margin-top:0px;margin-left:0px;display:inline-block;text-align:center;line-height:100px;font-size:50px;">
								<img src="%sitename%/wp-content/plugins/book-appointment-online/images/booked600x200.jpg" width="600" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px;">
							</div>
						</div>
						<div class="mob100" style="max-height:0;max-width:0;overflow: visible;">
							<div class="mob100" style="width:560px;height:200px;margin-top:0px;margin-left:20px;display:table;text-align:center;">
								<h3 class="h3" style="font-family: sans-serif;color: #fff;font-size: 36px;text-align: center;margin: auto;vertical-align: middle;display: table-cell;"><?php _e('New booking on %date%', 'book-appointment-online'); ?></h3>
							</div>
						</div>
					</div>  
				</td>
            </tr>
            <!-- Hero Image, Flush : END -->

            <!-- 1 Column Text : BEGIN -->
            <tr>
                <td style="padding: 20px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
					<h1><?php _e('New booking from %name%', 'book-appointment-online'); ?></h1>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </td>
            </tr>
			<!---order start-->
			<tr>
				<td style="padding:20px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tbody>
		<tr>
			<th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" width="270" valign="top">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr>
					<td>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6e52e5">
							<tbody><tr>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

									<div class="text-1" style="color:#fff; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
										<strong><?php _e('Order details', 'book-appointment-online'); ?>:</strong>
									</div>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

								</td>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</tbody></table>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#fafafa">
							<tbody><tr>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

									<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
										<strong><?php _e('Service', 'book-appointment-online'); ?>: </strong>%service%<br>
										<strong><?php _e('Employee', 'book-appointment-online'); ?>: </strong>%specialist%<br>
										<strong><?php _e('Date', 'book-appointment-online'); ?>: </strong>%date% %time%<br>
										<strong><?php _e('Duration (min)', 'book-appointment-online'); ?>: </strong>%duration%<br>
									</div>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="15">&nbsp;</td></tr></tbody></table>

								</td>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
		</th>
		<th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" width="20" valign="top">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr>
					<td><div style="font-size:0pt; line-height:0pt;" class="mobile-br-15"></div>
	</td>
				</tr>
			</tbody></table>
		</th>
		<th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" width="270" valign="top">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr>
					<td>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6e52e5">
							<tbody><tr>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

									<div class="text-1" style="color:#fff; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
										<strong><?php _e('Order number', 'book-appointment-online'); ?>:</strong> <span style="color: #fff;">%id%</span>
									</div>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

								</td>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</tbody></table>
						<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="20">&nbsp;</td></tr></tbody></table>


						<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6e52e5">
							<tbody><tr>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

									<div class="text-1" style="color:#fff; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
										<strong><?php _e('Client data', 'book-appointment-online'); ?>:</strong>
									</div>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

								</td>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</tbody></table>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#fafafa">
							<tbody><tr>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
								<td>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="10">&nbsp;</td></tr></tbody></table>

									<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
										<strong>%name%</strong> %phone% <br>
										email: %email%
									</div>
									<table class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" height="15">&nbsp;</td></tr></tbody></table>

								</td>
								<td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
			</th>
		</tr>
	</tbody>
</table>
</td>
</tr>
<!--order end-->
        </table>
        <!-- Email Body : END -->
    </center>
</body>
</html>

