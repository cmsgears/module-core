<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Sites | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Sites', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'order' => 'Order', 'active' => 'Active'
	],
	'filters' => [ 'status' => [ 'file' => 'File Render', 'layout' => 'Group Layout' ] ],
	'reportColumns' => [
		'status' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'active' => 'Activate', 'block' => 'Blocked' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x6', null, null, 'x5', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'order' => 'Order',
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'theme' => [ 'title' => 'Theme', 'generate' => function( $model ) { return $model->theme->name; } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/site",
	//'cardView' => "$moduleTemplates/grid/cards/site",
	'actionView' => "$moduleTemplates/grid/actions/site"
]) ?>

<?= Popup::widget([
	'title' => 'Update Sites', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Site', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/sites/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Site', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Site', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/sites/delete?id=" ]
]) ?>
