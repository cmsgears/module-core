<?php
$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Dashboard | ' . $coreProperties->getSiteTitle();

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-dashboard';
$this->params['sidebar-child']	= 'dashboard';
?>