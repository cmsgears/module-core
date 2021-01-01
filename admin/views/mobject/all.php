<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$baseUrl		= $this->context->baseUrl;
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'baseUrl' => $baseUrl, 'add' => true, 'addUrl' => "create?pid={$parent->id}", 'data' => [ 'parent' => $parent ],
	'title' => $this->title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content'
	],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title', 'status' => 'Status',
		'visibility' => 'Visibility', 'order' => 'Order', 'active' => 'Active',
		'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'status' => $filterStatusMap,
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular'
		]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'visibility' => [ 'title' => 'Visibility', 'type' => 'select', 'options' => $visibilityMap ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'popular' => [ 'title' => 'Popular', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		//'status' => [ 'activate' => 'Activate', 'block' => 'Block', 'terminate' => 'Terminated' ],
		'model' => [
			'activate' => 'Activate', 'disable' => 'Disable',
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x3', 'x3', null, null, null, null, null, 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			$icon = "<div class='align align-center'><i class=\"{$model->model->icon}\"></i></div>" ; return $icon;
		}],
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->model->name; } ],
		'title' => [ 'title' => 'Title', 'generate' => function( $model ) { return $model->model->title; } ],
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->model->getVisibilityStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->model->getStatusStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'popular' => [ 'title' => 'Popular', 'generate' => function( $model ) { return $model->getPopularStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/mobject",
	//'cardView' => "$moduleTemplates/grid/cards/mobject",
	'actionView' => "$moduleTemplates/grid/actions/mobject"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?pid=$parent->id&id=" ]
])?>
