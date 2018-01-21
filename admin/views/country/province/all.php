<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Provinces | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?cid=$countryId", 'data' => [ ],
	'title' => 'Provinces', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'code' => 'Code' ],
	'sortColumns' => [
		'name' => 'Name', 'code' => 'Code', 'iso' => 'ISO'
	],
	'filters' => [],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'code' => [ 'title' => 'Code', 'type' => 'text' ],
		'iso' => [ 'title' => 'ISO', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [ 'model' => [ 'delete' => 'Delete' ] ],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x9', 'x2', 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'code' => 'Code',
		'iso' => 'ISO',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/country",
	//'cardView' => "$moduleTemplates/grid/cards/country",
	'actionView' => "$moduleTemplates/grid/actions/province"
]) ?>

<?= Popup::widget([
	'title' => 'Update Provinces', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Province', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/country/province/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Province', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Province', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/country/province/delete?id=" ]
]) ?>
