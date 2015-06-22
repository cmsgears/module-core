<?php
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Settings';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-setting';
$this->params['sidebar-child'] 	= $type;
?>