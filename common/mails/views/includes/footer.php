<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" style="background-color: #F2F2F2;" class="ctmax">
	<tr><td height="20" colspan="2"></td></tr>
	<tr>
		<td align="center" colspan="2" style="padding: 20px; color: #7F8995;">
			<div style="border-bottom: 1px solid #D8D8D8; font-size: 12px;">
				<p style="margin: 0; text-align: center; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif">This email was sent to: <span style="font-weight: bold; color: #35A7ED;"><?= $email ?></span></font></p>
				<?php if( isset( $user ) ) { ?>
					<p style="margin: 0; text-align: center; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif">This email was intended for the user <?= $user->getName() ?></font></p>
				<?php } ?>
			</div>
		</td>
	</tr>
	<tr><td height="20" colspan="2"></td></tr>
	<tr>
		<td style="padding: 0 20px; color: #7F8995;" class="ctcolmax">
			<p style="margin: 0; font-size: 12px; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif">Warm Regards</span></font></p>
			<p style="margin: 0; font-size: 16px; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif"><a href="<?= $homeUrl ?>" style="color: #35A7ED; text-decoration: none;"><?= $siteName ?></a></font></p>
		</td>
		<td style="padding: 0 20px; color: #7F8995;" class="ctcolmax">
			<p style="margin: 0; text-align: right; font-size: 12px; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif">Customer Support</font></p>
			<p style="margin: 0; text-align: right; font-size: 16px; margin-bottom: 10px;"><font face="'Roboto', Arial, sans-serif"><a href="<?= $homeUrl ?>/form/contact-us" style="color: #35A7ED; text-decoration: none;">Contact Us</a></font></p>
		</td>
	</tr>
	<tr><td height="10" colspan="2"></td></tr>
	<tr>
		<td style="text-align: center; font-size: 12px;" colspan="2">
			<font face="'Roboto', Arial, sans-serif">
				<a href="<?= $homeUrl ?>/about-us" style="color: #35A7ED; text-decoration: none;">About Us</a> &nbsp; | &nbsp;
				<a href="<?= "$homeUrl/privacy" ?>" style="color: #35A7ED; text-decoration: none;">Privacy Policy</a> &nbsp; | &nbsp;
				<a href="<?= "$homeUrl/terms" ?>" style="color: #35A7ED; text-decoration: none;">Terms & Conditions</a>
			</font>
		</td>
	</tr>
	<tr><td height="30" colspan="2"></td></tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" style="background-color: #2A3744; color: #FFFFFF;" class="ctmax">
	<tr><td height="20"></td></tr>
	<tr>
		<td style="text-align: center; font-size: 12px;">
			<font face="'Roboto', Arial, sans-serif">Copyright © 2016 - <?= date( 'Y' ) ?> <?= $siteName ?>. All Rights Reserved.</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
</table>
