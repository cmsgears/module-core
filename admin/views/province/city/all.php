<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Cities | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid=$provinceId", 'data' => [ ],
	'title' => 'Cities', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'code' => 'Code' ],
	'sortColumns' => [
		'name' => 'Name', 'zone' => 'Zone', 'postal' => 'Postal Code',
		'latitude' => 'Latitude', 'longitude' => 'Longitude'
	],
	'filters' => [],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'zone' => [ 'title' => 'Zone', 'type' => 'text' ],
		'postal' => [ 'title' => 'Postal Code', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [ 'model' => [ 'delete' => 'Delete' ] ],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x5', 'x2', 'x2', 'x2', 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'zone' => 'Zone',
		'postal' => 'Postal Code',
		'latitude' => 'Latitude',
		'longitude' => 'Longitude',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/country",
	//'cardView' => "$moduleTemplates/grid/cards/country",
	//'actionView' => "$moduleTemplates/grid/actions/city"
]) ?>

<?= Popup::widget([
	'title' => 'Update Cities', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'City', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/province/city/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete City', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'City', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/province/city/delete?id=" ]
]) ?>
