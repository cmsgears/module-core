<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Sites | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?sid=$siteId", 'data' => [ ],
	'title' => 'Sites', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [  ],
	'sortColumns' => [
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
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', 'x2', 'x2', null, null, 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) {
			return $model->user->getName();
		}],
		'email' => [ 'title' => 'Email', 'generate' => function( $model ) {
			return $model->user->email;
		}],
		'site' => [ 'title' => 'Site', 'generate' => function( $model ) {
			return $model->site->name;
		}],
		'roleId' => [ 'title' => 'Role', 'generate' => function( $model ) {
			return $model->role->name;
		}],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/site",
	//'cardView' => "$moduleTemplates/grid/cards/site",
	//'actionView' => "$moduleTemplates/grid/actions/site"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Site Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Site Member', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Site Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
