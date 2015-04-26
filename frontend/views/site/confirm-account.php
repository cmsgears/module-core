<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Confirm Account";
?>
<h1>Confirm Account</h1>
<?php echo Yii::$app->session->getFlash( "message" ); ?>