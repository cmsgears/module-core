<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;


$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Files | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Templates', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'extension' => 'Extension', 'directory' => 'Directory' ],
	'sortColumns' => [
		'title' => 'Title', 'extension' => 'Extension', 'directory' => 'Directory', 'size' => 'Size',
		'url' => 'Path', 'visibility' => 'Visibility',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'visibility' => [ 'public' => 'Public', 'protected' => 'Protected', 'private' => 'Private' ] ],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'extension' => [ 'title' => 'Extension', 'type' => 'text' ],
		'directory' => [ 'title' => 'Directory', 'type' => 'text' ],
		'visibility' => [ 'title' => 'Visibility', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [ 'visibility' => [ 'public' => 'Public', 'protected' => 'Protected', 'private' => 'Private' ] ],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x4', 'x2', 'x2', 'x4', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Name',
		'directory' => 'Directory',
		'extension' => 'Extension',
		'url' => 'Path',
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->getVisibilityStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/file",
	//'cardView' => "$moduleTemplates/grid/cards/file",
	//'actionView' => "$moduleTemplates/grid/actions/file"
]) ?>

<?= Popup::widget([
	'title' => 'Update Files', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'File', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/file/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete File', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'File', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/file/delete?id=" ]
]) ?>
