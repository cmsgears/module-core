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

$email = $comment->email;
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear Admin,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">A feedback form has been submitted. The details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Name: <?= $comment->name ?></font></td>
	</tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Email: <?= $comment->email ?></font></td>
	</tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Message: <?= $comment->content ?></font></td>
	</tr>
	<tr>
		<td> <font face="Roboto, Arial, sans-serif">Ratings: <?= $comment->rating ?></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
