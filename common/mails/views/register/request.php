<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$siteProperties = Yii::$app->controller->getSiteProperties();

$name	= Html::encode( $user->getName() );
$email	= Html::encode( $user->email );
$token	= Html::encode( $user->verifyToken );

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/" . $siteProperties->getMailAvatar(), true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$confirmLink = Url::toRoute( "/confirm-account?token=$token&email=$email", true );
?>
<?php include dirname( __DIR__ ) . '/includes/header.php'; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear <?= $name ?>,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">Thank you for registering with <?= $siteName ?>. Your account details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Email: <?= $email ?></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Confirmation Link: <a href="<?= $confirmLink ?>">Confirm Account</a></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include dirname( __DIR__ ) . '/includes/footer.php'; ?>
