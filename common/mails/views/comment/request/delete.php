<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$name		= Html::encode( $user->getName() );
$email		= Html::encode( $user->email );

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/logo-mail.png", true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/banner-mail.jpg";

$adminUrl	= Html::encode( $coreProperties->getAdminUrl() );
?>
<?php include dirname( __DIR__ ) . '/includes/header.php'; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="'Roboto', Arial, sans-serif">Dear <?= $name ?>,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="'Roboto', Arial, sans-serif">Delete Comment Request received. <a href="<?= "$adminUrl/$updatePath?id=$comment->id" ?>">Click</a> here to review.</font>
		</td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include dirname( __DIR__ ) . '/includes/footer.php'; ?>
