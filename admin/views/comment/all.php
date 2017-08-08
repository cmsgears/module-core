<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\widgets\popup\Popup;
use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$type			= ucfirst( $this->context->commentType );
$types			= $type . 's';
$this->title	= "$types | " . $coreProperties->getSiteTitle();
$parentUrl 		= $this->context->parentUrl;

// Templates
$moduleTemplates	= '@cmsgears/module-cms/admin/views/templates';

$pid	= $parent ? $model->id : null;
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => $parent, 'addUrl' => "create?pid=$pid", 'data' => [ ],
	'title' => $types, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'user' => 'User', 'name' => 'Name', 'email' => 'Email', 'content' => 'Content' ],
	'sortColumns' => [
		'user' => 'User', 'name' => 'Name',  'email' => 'Email', 'status' => 'Status', 'rating' => 'Rating', 'featured' => 'Featured',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'adate' => 'Approved At'
	],
	'filters' => [ 'status' => [ 'new' => 'New', 'spam' => 'Spam', 'blocked' => 'Blocked', 'approved' => 'Approved', 'trash' => 'Trash' ] ],
	'reportColumns' => [
		'user' => [ 'title' => 'User', 'type' => 'text' ],
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => ModelComment::$statusMap ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'rating' => [ 'title' => 'Rating', 'type' => 'text' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'spam' => 'Spam', 'block' => 'Block', 'approve' => 'Approve', 'trash' => 'Trash' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x2', 'x2', 'x2', null, 'x2', 'x4', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) { return isset( $model->user ) ? $model->user->firstName . ' ' . $model->user->lastName : null; } ],
		'name' => 'Name',
		'email' => 'Email',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'parent' => [ 'title' => 'Parent', 'generate' => function( $model ) use( &$parent, &$parentUrl ) {
			if( $parent ) {
				return "<a href='". Url::toRoute( [ $parentUrl . $model->parentId ], true ). "'>parent</a>";
			}
		}],
		'message' => [ 'title' => 'Message', 'generate' => function( $model ) { return $model->content; } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/comment",
	//'cardView' => "$moduleTemplates/grid/cards/comment",
	//'actionView' => "$moduleTemplates/grid/actions/comment"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => $type, 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "core/comment/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => "Delete $type", 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => $type, 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "core/comment/delete?id=" ]
]) ?>
