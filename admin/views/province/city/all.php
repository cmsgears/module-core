<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Cities | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid=$provinceId", 'data' => [ ],
	'title' => 'Cities', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'code' => 'Code', 'iso' => 'ISO', 'postal' => 'Postal Code', 'zone' => 'Zone' ],
	'sortColumns' => [
		'name' => 'Name', 'code' => 'Code', 'iso' => 'ISO', 'postal' => 'Postal Code', 'zone' => 'Zone',
		'latitude' => 'Latitude', 'longitude' => 'Longitude'
	],
	'filters' => [],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'code' => [ 'title' => 'Code', 'type' => 'text' ],
		'iso' => [ 'title' => 'ISO', 'type' => 'text' ],
		'postal' => [ 'title' => 'Postal Code', 'type' => 'text' ],
		'zone' => [ 'title' => 'Zone', 'type' => 'text' ],
		'regions' => [ 'title' => 'Regions', 'type' => 'text' ],
		'zcodes' => [ 'title' => 'Zip Codes', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x2', null, 'x2', 'x2', null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'region' => [ 'title' => 'Region', 'generate' => function( $model ) {
			return isset( $model->region ) ? $model->region->name : null;
		}],
		'code' => 'Code',
		'iso' => 'ISO',
		'zone' => 'Zone',
		'postal' => 'Postal Code',
		'latitude' => 'Latitude',
		'longitude' => 'Longitude',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/country",
	//'cardView' => "$moduleTemplates/grid/cards/country",
	//'actionView' => "$moduleTemplates/grid/actions/city"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'City', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete City', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'City', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
