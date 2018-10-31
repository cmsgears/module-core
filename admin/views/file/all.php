<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Files | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Templates', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'desc' => 'Description', 'extension' => 'Extension', 'directory' => 'Directory' ],
	'sortColumns' => [
		'title' => 'Title', 'extension' => 'Extension', 'directory' => 'Directory',
		'size' => 'Size', 'url' => 'Path', 'visibility' => 'Visibility',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'visibility' => [ 'public' => 'Public', 'protected' => 'Protected', 'private' => 'Private' ] ],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'extension' => [ 'title' => 'Extension', 'type' => 'text' ],
		'directory' => [ 'title' => 'Directory', 'type' => 'text' ],
		'visibility' => [ 'title' => 'Visibility', 'type' => 'select', 'options' => $visibilityMap ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'visibility' => [ 'public' => 'Public', 'protected' => 'Protected', 'private' => 'Private' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', 'x3', null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'embed' => [ 'title' => 'Embed', 'generate' => function( $model ) { return $model->getFileUrl(); } ],
		'directory' => 'Directory',
		'extension' => 'Extension',
		'type' => 'Type',
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->getVisibilityStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/file",
	//'cardView' => "$moduleTemplates/grid/cards/file",
	//'actionView' => "$moduleTemplates/grid/actions/file"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'File', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete File', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'File', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
