<?php
// Yii Imports
use yii\helpers\Html;

$siteProperties = Yii::$app->controller->getSiteProperties();

$defaultIncludes = Yii::getAlias( '@cmsgears' ) . '/module-core/common/mails/views/includes';

$name	= Html::encode( $user->getName() );
$email	= Html::encode( $user->email );

$siteName	= Html::encode( $coreProperties->getSiteName() );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$logoUrl	= "$siteUrl/images/" . $siteProperties->getMailAvatar();
$homeUrl	= $siteUrl;
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();

$adminUrl = Html::encode( $coreProperties->getAdminUrl() );
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear Administrator,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Delete Comment Request received. <a href="<?= "$adminUrl/$updatePath?id=$comment->id" ?>">Click Here</a> to review.</font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
