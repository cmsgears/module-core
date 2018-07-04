<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/logo-mail.png", true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/banner-mail.jpg";

$user		= $model->holder ?? $model->creator;
$userName	= Html::encode( $user->getName() );
$modelName	= Html::encode( $model->name );

if( $message == null ) {

	$message = 'No reason was mentioned.';
}
?>
<?php include dirname( __DIR__ ) . '/includes/header.php'; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="'Roboto', Arial, sans-serif">Dear Member,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="'Roboto', Arial, sans-serif">Congratulations, <?= $modelName ?> has been blocked. The details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="'Roboto', Arial, sans-serif">Manager: <?= $userName ?></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="'Roboto', Arial, sans-serif">Reason: <?= $message ?></font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="'Roboto', Arial, sans-serif">Please contact <?= $siteName ?> Administrator in case it's not related to you.</font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include dirname( __DIR__ ) . '/includes/footer.php'; ?>
