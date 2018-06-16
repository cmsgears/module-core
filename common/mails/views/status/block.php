<?php
use yii\helpers\Html;
use yii\helpers\Url;

$logoUrl	= Url::to( "@web/images/logo-mail.png", true );

$logo		= "<img class=\"logo\" style=\"margin:10px;\" src=\"$logoUrl\">";
$siteName	= Html::encode( $coreProperties->getSiteName() );

$user		= $model->holder ?? $model->creator;
$userName	= Html::encode( $user->getName() );
$modelName	= Html::encode( $model->name );

if( $message == null ) {

	$message = 'No reason was mentioned.';
}
?>
<table cellspacing='0' cellpadding='2' border='0' align='center' width='805px' style='font-family: Calibri; color: #4f4f4f; font-size: 14px; font-weight: 400;'>
	<tbody>
		<tr>
			<td>
				<div style='width:100%; margin:0 auto; min-height:45px; background-color:#f6f9f4; text-align: center;'>
					<?= $logo ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style='margin-top:60px;'>Dear <?= $userName ?>,</div>
			</td>
		</tr>
		<tr>
			<td>
				<br/><?= $modelName ?> is blocked by Admin.
			</td>
		</tr>
		<tr>
			<td>
				<br/>The reason for block is - <?= $message ?>.
			</td>
		</tr>
		<tr>
			<td>
				<div style='line-height:15px; margin:0px; padding:0px; margin-top:30px;'>Sincerely,</div>
				<div style='line-height:15px; margin:0px; padding:0px; margin-top:3px;'><?=$siteName?> Team</div>
			</td>
		</tr>
	</tbody>
</table>