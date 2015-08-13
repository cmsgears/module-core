<?php
$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Dashboard";

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-dashboard';
$this->params['sidebar-child'] 	= 'dashboard';
?>