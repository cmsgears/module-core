<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$name		= Html::encode( $user->getName() );
$email		= Html::encode( $user->email );
$token		= Html::encode( $user->verifyToken );

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/logo-mail.png", true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/banner-mail.jpg";

if( $user->isPermitted( CoreGlobal::PERM_ADMIN ) ) {

	$siteUrl = Html::encode( $coreProperties->getAdminUrl() );
}

$activateLink = "$siteUrl/activate-account?token=$token&email=$email";
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
			<font face="'Roboto', Arial, sans-serif">Your account was created at <?= $siteName ?> by Administrator. Your account details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="'Roboto', Arial, sans-serif">Email: <?= $email ?></font></td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td> <font face="'Roboto', Arial, sans-serif">Activation Link: <a href="<?= $activateLink ?>">Activate Account</a></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include dirname( __DIR__ ) . '/includes/footer.php'; ?>
