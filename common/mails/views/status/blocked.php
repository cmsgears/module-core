<?php
// Yii Imports
use yii\helpers\Html;

$siteProperties = Yii::$app->controller->getSiteProperties();

$defaultIncludes = Yii::getAlias( '@cmsgears' ) . '/module-core/common/mails/views/includes';

$siteName	= Html::encode( $coreProperties->getSiteName() );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$logoUrl	= "$siteUrl/images/" . $siteProperties->getMailAvatar();
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$user		= isset( $model->holder ) ? $model->holder : $model->creator;
$name		= Html::encode( $user->getName() );
$email		= Html::encode( $user->email );
$modelName	= Html::encode( $model->getDisplayName() );

if( $message == null ) {

	$message = 'No reason was mentioned.';
}
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear Member,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">Unfortunately, <?= $modelName ?> has been frozen for further activities. It won't be functional until the issues mentioned by Administrator are resolved. The details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Manager: <?= $name ?></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Reason: <?= $message ?></font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Please contact <?= $siteName ?> Administrator in case it's not related to you.</font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
