<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}es | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid={$parent->id}", 'data' => [ 'parent' => $parent ],
	'title' => $this->title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'line1' => 'Line 1' ],
	'sortColumns' => [
		'title' => 'Title', 'line1' => 'Line 1', 'country' => 'Country',
		'province' => 'Province', 'region' => 'Region', 'city' => 'City'
	],
	'filters' => [
		'model' => [ 'active' => 'Active' ]
	],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'line1' => [ 'title' => 'Line 1', 'type' => 'text' ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		//'model' => [ 'active' => 'Activate', 'disabled' => 'Disable', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x2', 'x2', 'x2', null, 'x2', null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => [ 'title' => 'Title', 'generate' => function( $model ) { return $model->model->title; } ],
		'line1' => [ 'title' => 'Line 1', 'generate' => function( $model ) { return $model->model->line1; } ],
		'country' => [ 'title' => 'Country', 'generate' => function( $model ) { return $model->model->countryName; } ],
		'province' => [ 'title' => Yii::$app->core->provinceLabel, 'generate' => function( $model ) { return $model->model->provinceName; } ],
		'region' => [ 'title' => Yii::$app->core->regionLabel, 'generate' => function( $model ) { return $model->model->regionName; } ],
		'city' => [ 'title' => 'City', 'generate' => function( $model ) { return $model->model->cityName; } ],
		'type' => [ 'title' => 'Type', 'generate' => function( $model ) use( $typeMap ) { return $typeMap[ $model->type ]; } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/maddress",
	//'cardView' => "$moduleTemplates/grid/cards/maddress",
	'actionView' => "$moduleTemplates/grid/actions/maddress"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Address', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?pid=$parent->id&id=" ]
])?>
