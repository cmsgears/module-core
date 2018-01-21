<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Permission Groups | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Permissions', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'desc' => 'Description' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	//'filters' => [ 'status' => [ 'group' => 'Group' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'slug' => [ 'title' => 'Slug', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'block' => 'Block', 'active' => 'Activate' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', null, 'x2', 'x8', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) { return "<i class=\"$model->icon\"></i>"; } ],
		'slug' => 'Slug',
		'description' => 'Description',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/permission",
	//'cardView' => "$moduleTemplates/grid/cards/permission",
	//'actionView' => "$moduleTemplates/grid/actions/permission"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Permission', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/permission/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Permission', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Permission', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/permission/delete?id=" ]
]) ?>
