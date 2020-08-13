<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid=$parent->id", 'data' => [ 'parent' => $parent ],
	'title' => 'Templates', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'title' => 'Title', 'desc' => 'Description', 'caption' => 'Caption',
		'extension' => 'Extension', 'directory' => 'Directory'
	],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title',
		'directory' => 'Directory', 'extension' => 'Extension',
		'ftype' => 'File Type', 'active' => 'Active',
		'visibility' => 'Visibility', 'url' => 'Embed Url'
	],
	'filters' => [
		'ftype' => $typeMap,
		'visibility' => $visibilityMap,
		'model' => [
			'active' => 'Active', 'disabled' => 'Disabled',
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular'
		]
	],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'caption' => [ 'title' => 'Caption', 'type' => 'text' ],
		'extension' => [ 'title' => 'Extension', 'type' => 'text' ],
		'directory' => [ 'title' => 'Directory', 'type' => 'text' ],
		'visibility' => [ 'title' => 'Visibility', 'type' => 'select', 'options' => $visibilityMap ],
		'ftype' => [ 'title' => 'File Type', 'type' => 'select', 'options' => $typeMap ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'popular' => [ 'title' => 'Popular', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		//'visibility' => [ 'public' => 'Public', 'protected' => 'Protected', 'private' => 'Private' ],
		'model' => [
			'activate' => 'Activate', 'disable' => 'Disable',
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x2', null, null, null, null, null, 'x2', 'x4', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->model->name; } ],
		'title' => [ 'title' => 'Title', 'generate' => function( $model ) { return $model->model->title; } ],
		'directory' => [ 'title' => 'Directory', 'generate' => function( $model ) { return $model->model->directory; } ],
		'extension' => [ 'title' => 'Extension', 'generate' => function( $model ) { return $model->model->extension; } ],
		'ftype' => [ 'title' => 'Type', 'generate' => function( $model ) { return $model->model->type; } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->model->getVisibilityStr(); } ],
		'embed' => [ 'title' => 'Embed Url', 'generate' => function( $model ) { return $model->model->getFileUrl(); } ],
		'code' => [ 'title' => 'Embed Code', 'generate' => function( $model ) {
			$code = $model->model->getEmbeddableCode();
			return !empty( $code ) ? "<pre class=\"height height-small\">" . htmlentities( $code ) . "</pre>" : null;
		}],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/mfile",
	//'cardView' => "$moduleTemplates/grid/cards/mfile",
	'actionView' => "$moduleTemplates/grid/actions/mfile"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete File', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?pid=$parent->id&id=" ]
]) ?>
