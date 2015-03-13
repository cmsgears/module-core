<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Confirm Account";
?>
<div class="common-public-wrapper clearfix">
	<div class="page-wrapper clearfix">
		<div class="form-block-wrapper clearfix">
			<div class="module-header">
				<h1 class="align-middle">Confirm  Account</h1>
			</div>
			<div class="module-content">
				<P>
					<?php echo Yii::$app->session->getFlash( "message" ); ?> 
				</P>
			</div>
		</div>
	</div>
</div>