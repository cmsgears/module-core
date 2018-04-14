<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$btitle			= $title . 's';
$this->title	= "$btitle | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid=$parent->id", 'data' => [ 'parent' => $parent ],
	'title' => 'Countries', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'label' => 'Label', 'value' =>'Value' ],
	'sortColumns' => [
		'name' => 'Name', 'label' => 'Label', 'type' => 'Type', 'active' => 'Active', 'vtype' => 'valueType'
	],
	'filters' => [],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'label' => [ 'title' => 'Label', 'type' => 'text' ],
		'type' => [ 'title' => 'Type', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ],
		'vtype' => [ 'title' => 'Value Type', 'type' => 'select', 'options' => $typeMap ],
		'value' => [ 'title' => 'Value', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x4', null, 'x2', 'x2', 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'label' => 'Label',
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'type' => 'Type',
		'vtype' => [ 'title' => 'Value Type', 'generate' => function( $model ) { return $model->getValueTypeStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/attribute",
	//'cardView' => "$moduleTemplates/grid/cards/attribute",
	'actionView' => "$moduleTemplates/grid/actions/attribute"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/attribute/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/attribute/delete?id=" ]
]) ?>
