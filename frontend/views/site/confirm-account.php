<?php
$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . ' | Confirm Account';
?>
<h1>Confirm Account</h1>
<p><?= Yii::$app->session->getFlash( 'message' ) ?></p>