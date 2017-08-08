<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Roles | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-cms/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Roles', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'desc' => 'Description' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'admin' => 'Admin Url', 'home' => 'Home Url',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	//'filters' => [ 'status' => [ 'group' => 'Group' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'slug' => [ 'title' => 'Slug', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x2', null, 'x2', 'x4', 'x2', 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) { return "<i class=\"$model->icon\"></i>"; } ],
		'slug' => 'Slug',
		'description' => 'Description',
		'adminUrl' => 'Admin URL',
		'homeUrl' => 'Home URL',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/role",
	//'cardView' => "$moduleTemplates/grid/cards/role",
	//'actionView' => "$moduleTemplates/grid/actions/role"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Role', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/role/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Role', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Role', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/role/delete?id=" ]
]) ?>
