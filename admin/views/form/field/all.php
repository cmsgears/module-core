<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Form Fields | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?fid=$formId", 'data' => [ ],
	'title' => 'Form Fields', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title' ],
	'sortColumns' => [
		'name' => 'Name', 'label' => 'Label',
		'compress' => 'Compress', 'active' => 'Active', 'order' => 'Order'
	],
	'filters' => [
		'status' => [ 'active' => 'Active', 'inactive' => 'Inactive', 'compress' => 'Compress' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'label' => [ 'title' => 'Label', 'type' => 'text' ],
		'type' => [ 'title' => 'Type', 'type' => 'select', 'options' => $typeMap ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ],
		'compress' => [ 'title' => 'Compress', 'type' => 'flag' ],
		'validators' => [ 'title' => 'Label', 'type' => 'text' ],
		'order' => [ 'title' => 'Order', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'active' => 'Activate', 'inactive' => 'Inactivate', 'compress' => 'Compress', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x3', 'x3', null, 'x3', null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			return "<div class='align align-center'><i class=\"$model->icon\"></i></div>" ;
		}],
		'name' => 'Name',
		'label' => 'Label',
		'type' => [ 'title' => 'Type', 'generate' => function( $model ) { return $model->getTypeStr();  } ],
		'validators' => 'Validators',
		'active' => 'Active',
		'compress' => 'Compress',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/field",
	//'cardView' => "$moduleTemplates/grid/cards/field",
	//'actionView' => "$moduleTemplates/grid/actions/field"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Form Field', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Form Field', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Form Field', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
