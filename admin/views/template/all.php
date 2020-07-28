<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$type = ucfirst( $type );

$coreProperties = $this->context->getCoreProperties();
$title			= !empty( $this->context->title ) ? $this->context->title : $type;
$this->title	= "$title Templates | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => "$title Templates", 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'title' => 'Title', 'active' => 'Active', 'renderer' => 'Renderer',
		'frender' => 'File Render', 'layout' => 'Layout', 'lgroup' => 'Layout Group', 'vpath' => 'View Path',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'model' => [ 'active' => 'Active', 'frender' => 'File Render', 'lgroup' => 'Group Layout' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'renderer' => [ 'title' => 'Renderer', 'type' => 'select', 'options' => Yii::$app->templateManager->renderers ],
		'frender' => [ 'title' => 'File Render', 'type' => 'flag' ],
		'layout' => [ 'title' => 'Layout', 'type' => 'text' ],
		'lgroup' => [ 'title' => 'Layout Group', 'type' => 'flag' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ],
		'cdate' => [ 'title' => 'Created At', 'type' => 'date' ],
		'udate' => [ 'title' => 'Updated At', 'type' => 'date' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'active' => 'Activate', 'inactive' => 'Disable', 'frender' => 'File Render', 'crender' => 'Content Render', 'group' => 'Layout Group', 'single' => 'Single Layout', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x2', null, null, null, null, null, null, 'x2', 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'frender' => [ 'title' => 'File', 'generate' => function( $model ) { return $model->getFileRenderStr(); } ],
		'lgroup' => [ 'title' => 'Grouped Layout', 'generate' => function( $model ) { return $model->getGroupLayoutStr(); } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'frontend' => [ 'title' => 'Frontend', 'generate' => function( $model ) { return $model->getFrontendStr(); } ],
		'renderer' => 'Renderer',
		'layout' => 'Layout',
		'viewPath' => 'View Path',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/template",
	//'cardView' => "$moduleTemplates/grid/cards/template",
	'actionView' => "$moduleTemplates/grid/actions/template"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Template', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Template', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Template', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
