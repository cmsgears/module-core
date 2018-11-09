<?php
$coreProperties = $this->context->getCoreProperties();
$themeIncludes	= Yii::getAlias( '@themes/admin/views/includes' );
$this->title 	= 'Gallery | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$returnUrl		= $this->context->returnUrl;

include "$themeIncludes/components/crud/gallery/card.php";
