<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Templates | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Templates', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'renderer' => 'Renderer', 'frender' => 'File Render',
		'layout' => 'Layout', 'lgroup' => 'Layout Group', 'vpath' => 'View Path',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'ldate' => 'Sent At'
	],
	'filters' => [ 'status' => [ 'file' => 'File Render', 'layout' => 'Group Layout' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'file' => [ 'title' => 'File Render', 'type' => 'flag' ],
		'layout' => [ 'title' => 'Layout Group', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'file' => 'File Render', 'cache' => 'Cache Render', 'group' => 'Layout Group', 'single' => 'Single Layout' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', null, 'x2', 'x3', null, 'x2', 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'file' => [ 'title' => 'File Render', 'generate' => function( $model ) { return $model->getFileRenderStr(); } ],
		'glayout' => [ 'title' => 'Layout Group', 'generate' => function( $model ) { return $model->getGroupLayoutStr(); } ],
		'description' => 'Description',
		'renderer' => 'Renderer',
		'layout' => 'Layout',
		'viewPath' => 'View Path',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/template",
	//'cardView' => "$moduleTemplates/grid/cards/template",
	//'actionView' => "$moduleTemplates/grid/actions/template"
]) ?>

<?= Popup::widget([
	'title' => 'Update Templates', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Template', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/template/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Template', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Template', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/template/delete?id=" ]
]) ?>
