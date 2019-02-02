<?php
// Yii Imports
use yii\helpers\Html;

$siteProperties = Yii::$app->controller->getSiteProperties();

$siteName	= Html::encode( $coreProperties->getSiteName() );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$logoUrl	= "$siteUrl/images/" . $siteProperties->getMailAvatar();
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$user		= $model->holder ?? $model->creator;
$name		= Html::encode( $user->getName() );
$email		= Html::encode( $user->email );
$modelName	= Html::encode( $model->getDisplayName() );

if( $message == null ) {

	$message = 'No reason was mentioned.';
}

$defaultIncludes = dirname( __DIR__ ) . '/includes';
?>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; ">
	<?php include "$defaultIncludes/header.php"; ?>
	<tr>
		<td bgcolor="#f5f5f5" style="padding: 0; border-bottom: 1px solid #a6a7a7;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color: #767676; padding: 20px 40px 20px 40px; font-family: latolight; font-size: 16px; line-height: 1.5;">
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
		</td>
	</tr>
	<?php include "$defaultIncludes/footer.php"; ?>
</table>
