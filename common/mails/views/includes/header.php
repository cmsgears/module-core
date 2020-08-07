<?php
// Yii Imports
use yii\helpers\Html;

$siteDesc = Html::encode( Yii::$app->core->site->description );
?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" style="background-color: #2A3744;" class="ctmax">
	<tr>
		<td background="<?= $siteBkg ?>" width="100%" class="ctcolmax">
			<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="100%" class="ctmax">
				<tr>
					<td valign="top" width="100%">
						<span width="30%" style="float: left; display: inline-block; padding: 10px;" class="ctcolmax">
							<img src="<?= $logoUrl ?>" border="0" margin="0" padding="0" />
						</span>
						<span width="60%" style="float: right; display: inline-block; padding: 10px; font-size: 16px; color: #FFFFFF;" class="ctcolmax">
							<font face="'Roboto', Arial, sans-serif">
								<a href="<?= $homeUrl ?>" style="color: #FFFFFF; text-decoration: none;">Home</a> &nbsp; &nbsp; | &nbsp; &nbsp;
								<a href="<?= "$homeUrl/login" ?>" style="color: #FFFFFF; text-decoration: none;">Login</a> &nbsp; &nbsp; | &nbsp; &nbsp;
								<a href="<?= "$homeUrl/register" ?>" style="color: #FFFFFF; text-decoration: none;">Register</a>
							</font>
						</span>
						<div class="clear:both;"></div>
					</td>
				</tr>
				<tr>
					<td valign="top" padding="10">
						<table cellspacing="0" cellpadding="10" border="0" margin="0" padding="0" width="100%" class="ctmax">
							<tr>
								<td align="center" style="font-size: 32px; color: #FFFFFF;">
									<font face="'Roboto', Arial, sans-serif"><?= $siteName ?></font>
								</td>
							</tr>
							<tr><td height="10"></td></tr>
							<tr>
								<td align="center" style="font-size: 24px; color: #FFFFFF;">
									<font face="'Roboto', Arial, sans-serif"><?= $siteDesc ?></font>
								</td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td align="center" valign="center" style="text-align: center;">
									<font face="'Roboto', Arial, sans-serif">
										<a href="<?= $homeUrl ?>" style="color: #FFFFFF; text-decoration: none;">
											<span style="font-size: 16px; color: #FFFFFF; border: 2px solid #FFFFFF; display: inline-block; width: 100px;">Explore</span>
										</a>
									</font>
								</td>
							</tr>
							<tr><td height="30"></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
