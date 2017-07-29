<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Community | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-cms/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Blocks', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'title' => 'Title', 'active' => 'Active',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'status' => [ 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'block' => 'Block', 'active' => 'Activate' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x2', null, 'x2', 'x2', 'x5', null, null  ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) { $icon = "<div class='align align-center'><i class='fa-2x " . $model->icon ."'></i></div>" ; return $icon;  } ],
		'slug' => 'Slug',
		'description' => 'Description',
		'adminUrl' => 'Admin URL',
		'homeUrl' => 'Home URL',	
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/gallery",
	//'cardView' => "$moduleTemplates/grid/cards/gallery",
	//'actionView' => "$moduleTemplates/grid/actions/Block"
]) ?>

<?= Popup::widget([
	'title' => 'Update Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "community/role/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "community/role/delete?id=" ]
]) ?>
