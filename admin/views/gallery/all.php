<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Galleries | ' . $coreProperties->getSiteTitle();

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Blocks', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title', 'status' => 'Status',
		'visibility' => 'Visibility', 'order' => 'Order', 'pinned' => 'Pinned', 'featured' => 'Featured'
	],
	'filters' => [
		'status' => [ 'new' => 'New', 'active' => 'Active', 'blocked' => 'Blocked' ],
		'model' => [ 'pinned' => 'Pinned', 'featured' => 'Featured' ]
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
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'confirmed' => 'Confirm', 'rejected' => 'Reject', 'active' => 'Activate', 'frozen' => 'Freeze', 'blocked' => 'Block' ],
		'model' => [ 'pinned' => 'Pinned', 'featured' => 'Featured', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, null, 'x3', 'x3', 'x2', null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'icon' => [ 'title' => 'Icon', 'generate' => function( $model ) {
			return "<div class='align align-center'><i class=\"$model->icon\"></i></div>" ;
		}],
		'name' => 'Name',
		'title' => 'Title',
		'template' => [ 'title' => 'Template', 'generate' => function( $model ) {
			return isset( $model->template ) ? $model->template->getName() : null;
		}],
		'visibility' => [ 'title' => 'Visibility', 'generate' => function( $model ) { return $model->getVisibilityStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/gallery",
	//'cardView' => "$moduleTemplates/grid/cards/gallery",
	'actionView' => "$moduleTemplates/grid/actions/gallery"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Gallery', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/gallery/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Gallery', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Gallery', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/gallery/delete?id=" ]
]) ?>
