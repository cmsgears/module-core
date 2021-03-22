<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Sites | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Sites', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'title' => 'Title', 'desc' => 'Description'
	],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title',
		'order' => 'Order', 'active' => 'Active',
		'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
		'primary' => 'Primary', 'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'model' => [
			'active' => 'Active', 'disabled' => 'Disabled',
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
			'primary' => 'Primary'
		]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'order' => [ 'title' => 'Order', 'type' => 'range' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'popular' => [ 'title' => 'Popular', 'type' => 'flag' ],
		'primary' => [ 'title' => 'Primary', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured',
			'popular' => 'Popular', 'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x2', 'x2', 'x2', null, null, null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			return "<div class='align align-center'><i class=\"$model->icon\"></i></div>" ;
		}],
		'name' => 'Name',
		'title' => 'Title',
		'theme' => [ 'title' => 'Theme', 'generate' => function( $model ) {
			return isset( $model->theme ) ? $model->theme->name : null;
		}],
		'order' => 'Order',
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'popular' => [ 'title' => 'Popular', 'generate' => function( $model ) { return $model->getPopularStr(); } ],
		'primary' => [ 'title' => 'Primary', 'generate' => function( $model ) { return $model->getPrimaryStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/site",
	//'cardView' => "$moduleTemplates/grid/cards/site",
	'actionView' => "$moduleTemplates/grid/actions/site"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Site', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Site', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Site', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
