<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$siteProperties = Yii::$app->controller->getSiteProperties();

$defaultIncludes = Yii::getAlias( '@cmsgears' ) . '/module-core/common/mails/views/includes';

$siteName	= Html::encode( $coreProperties->getSiteName() );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$logoUrl	= "$siteUrl/images/" . $siteProperties->getMailAvatar();
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$name	= Html::encode( $user->getName() );
$email	= Html::encode( $user->email );
$token	= Html::encode( $user->resetToken );

if( $user->isPermitted( CoreGlobal::PERM_ADMIN ) ) {

	$siteUrl = Html::encode( $coreProperties->getAdminUrl() );
}

$resetLink = "$siteUrl/reset-password?token=$token&email=$email";
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear <?= $name ?>,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">Reset password request was initiated for your account. Your account details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Email: <?= $email ?></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Reset Link: <a href="<?= $resetLink ?>">Reset Password</a></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
