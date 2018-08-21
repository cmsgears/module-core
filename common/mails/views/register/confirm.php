<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$siteProperties = Yii::$app->controller->getSiteProperties();

$name	= Html::encode( $user->getName() );
$email	= Html::encode( $user->email );

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/" . $siteProperties->getMailAvatar(), true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

if( $user->isPermitted( CoreGlobal::PERM_ADMIN ) ) {

	$siteUrl = Html::encode( $coreProperties->getAdminUrl() );
}

$loginLink	= "$siteUrl/login";
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
			<font face="Roboto, Arial, sans-serif">Thanks for confirming your account with us. Please <a href="<?= $loginLink ?>">Click Here</a> to login.</font>
		</td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include dirname( __DIR__ ) . '/includes/footer.php'; ?>
