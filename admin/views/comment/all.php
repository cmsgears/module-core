<?php
// Yii Imports
use yii\helpers\Url;

use cmsgears\widgets\popup\Popup;
use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$type			= ucfirst( $this->context->commentType );
$this->title	= "$title | " . $coreProperties->getSiteTitle();
$parentUrl 		= $this->context->parentUrl;
$apixBase		= $this->context->apixBase;

$add	= isset( $parent ) ? true : false;
$create	= isset( $parent ) ? "create?pid=$parent->id" : null;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => $add, 'addUrl' => $create, 'data' => [ 'apixBase' => $apixBase ],
	'title' => $title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'user' => 'User', 'name' => 'Name', 'email' => 'Email', 'content' => 'Content' ],
	'sortColumns' => [
		'user' => 'User', 'name' => 'Name', 'email' => 'Email',
		'status' => 'Status', 'rating' => 'Rating', 'pinned' => 'Pinned', 'featured' => 'Featured',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'adate' => 'Approved At'
	],
	'filters' => [
		'status' => [ 'new' => 'New', 'spam' => 'Spam', 'blocked' => 'Blocked', 'approved' => 'Approved', 'trash' => 'Trash' ],
		'model' => [ 'pinned' => 'Pinned', 'featured' => 'Featured' ]
	],
	'reportColumns' => [
		'user' => [ 'title' => 'User', 'type' => 'text' ],
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'rating' => [ 'title' => 'Rating', 'type' => 'range' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [ 'approved' => 'Approve', 'trash' => 'Trash', 'spam' => 'Spam', 'blocked' => 'Block' ],
		'model' => [ 'pinned' => 'Pinned', 'featured' => 'Featured', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x2', 'x2', 'x2', null, null, null, null, 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) {
			return isset( $model->user ) ? $model->user->firstName . ' ' . $model->user->lastName : null;
		}],
		'name' => 'Name',
		'email' => 'Email',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'parent' => [ 'title' => 'Parent', 'generate' => function( $model ) use( $parent, $parentUrl ) {
			if( $parent ) {
				return "<a href='". Url::toRoute( [ $parentUrl . $model->parentId ], true ). "'>View</a>";
			}
		}],
		'message' => [ 'title' => 'Message', 'generate' => function( $model ) { return $model->content; } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/comment",
	//'cardView' => "$moduleTemplates/grid/cards/comment",
	//'actionView' => "$moduleTemplates/grid/actions/comment"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $type, 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => "Delete $type", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $type, 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
