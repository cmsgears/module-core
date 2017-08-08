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
	'searchColumns' => [ 'name' => 'Name', 'username' => 'Username', 'email' => 'Email', 'phone' => 'Phone' ],
	'sortColumns' => [
		'name' => 'Name', 'username' => 'Username', 'email' => 'Email', 'phone' => 'Phone',
		'status' => 'Status', 'role' => 'Role', 'gender' => 'Gender',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'ldate' => 'Last Login'
	],
	'filters' => [ 'status' => [ 'new' => 'New', 'active' => 'Active', 'blocked' => 'Blocked' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'username' => [ 'title' => 'Username', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'phone' => [ 'title' => 'Phone', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'active' => 'Activate', 'block' => 'Block' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x2', 'x3', 'x2', null, 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return "$model->firstName $model->lastName"; } ],
		'username' => 'Username',
		'email' => 'Email',
		'role' => [ 'title' => 'Role', 'generate' => function( $model ) { return $model->role->name; } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'phone' => 'Phone',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/user",
	//'cardView' => "$moduleTemplates/grid/cards/user",
	//'actionView' => "$moduleTemplates/grid/actions/user"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'User', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/user/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Template', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'User', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/user/delete?id=" ]
]) ?>
