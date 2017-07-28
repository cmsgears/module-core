



<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;
use yii\helpers\Url;
use cmsgears\widgets\grid\DataGrid;
// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;
$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Post | ' . $coreProperties->getSiteTitle();
$parentUrl 		= $this->context->parentUrl;
// Templates
$moduleTemplates	= '@cmsgears/module-cms/admin/views/templates';
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => false, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Blocks', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name',  ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'active' => 'Active'
		
	],
	'filters' => [ 'status' => [ 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'block' => 'Block', 'active' => 'Activate' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x3', null, 'x2', 'x2', 'x2', null, null, null , null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'email' => 'Email',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'parent' => [ 'title' => 'Parent', 'generate' => function( $model ) { $parent = "<a href='". Url::toRoute( [ $parentUrl . $parentId ], true ). "'>parent</a>";  return $parent; } ],
		'message' => [ 'title' => 'Message', 'generate' => function( $model ) { return CodeGenUtil::getSummary( $model->content );; } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/gallery",
	//'cardView' => "$moduleTemplates/grid/cards/gallery",
	//'actionView' => "$moduleTemplates/grid/actions/post"
]) ?>

<?= Popup::widget([
	'title' => 'Update Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "cms/comment/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Block', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Block', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "cms/comment/delete?id=" ]
]) ?>
