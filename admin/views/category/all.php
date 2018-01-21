<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Categories | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Categories', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug'
	],
	'filters' => [ 'status' => [ 'featured' => 'Featured' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'featured' => 'Featured' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x3', null, 'x5', null, 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) { return "<i class=\"$model->icon\"></i>"; } ],
		'description' => 'Description',
		'featured'	=> [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'parent'	=> [ 'title' => 'Parent', 'generate' => function( $model ) { return $model->getParentName(); } ],
		'actions'	=> 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/category",
	//'cardView' => "$moduleTemplates/grid/cards/category",
	//'actionView' => "$moduleTemplates/grid/actions/category"
]) ?>

<?= Popup::widget([
	'title' => 'Update Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/category/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/category/delete?id=" ]
]) ?>
