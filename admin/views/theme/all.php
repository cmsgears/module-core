<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Themes | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Themes', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'slug' => 'Slug', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'default' => 'Default', 'renderer' => 'Renderer', 'base' => 'Base Path'
	],
	'filters' => [ 'status' => [ 'default' => 'Default' ] ],
	'reportColumns' => [
		'status' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x6', null, 'x3', 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'default' => [ 'title' => 'Default', 'generate' => function( $model ) { return $model->getDefaultStr(); } ],
		'renderer' => 'Renderer',
		'basePath' => 'Base Path',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/theme",
	//'cardView' => "$moduleTemplates/grid/cards/theme",
	'actionView' => "$moduleTemplates/grid/actions/theme"
]) ?>

<?= Popup::widget([
	'title' => 'Update Themes', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Theme', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/theme/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Update Theme', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'generic',
	'data' => [ 'model' => 'Theme', 'app' => 'main', 'controller' => 'crud', 'action' => 'generic', 'url' => "core/theme/generic?id=" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Theme', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Theme', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/theme/delete?id=" ]
]) ?>
