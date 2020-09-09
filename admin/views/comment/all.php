<?php
// Yii Imports
use yii\helpers\Url;

use cmsgears\widgets\popup\Popup;
use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$parentUrl 		= $this->context->parentUrl;
$parentCol 		= $this->context->parentCol;
$apixBase		= $this->context->apixBase;

$add	= isset( $parent ) ? true : false;
$create	= isset( $parent ) ? "create?pid=$parent->id" : null;

// View Templates
$moduleTemplates	= '@cmsgears/module-core/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => $add, 'addUrl' => $create, 'data' => [ 'apixBase' => $apixBase ],
	'title' => "{$title}s", 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'user' => 'User', 'name' => 'Name', 'email' => 'Email', 'content' => 'Content'
	],
	'sortColumns' => [
		'user' => 'User', 'name' => 'Name', 'email' => 'Email',
		'status' => 'Status', 'rating' => 'Rating',
		'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
		'anonymous' => 'Anonymous',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'adate' => 'Approved At'
	],
	'filters' => [
		'status' => [
			'new' => 'New', 'spam' => 'Spam', 'blocked' => 'Blocked',
			'approved' => 'Approved', 'trash' => 'Trash'
		],
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured',
			'popular' => 'Popular', 'anonymous' => 'Anonymous'
		]
	],
	'reportColumns' => [
		'user' => [ 'title' => 'User', 'type' => 'text' ],
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'rating' => [ 'title' => 'Rating', 'type' => 'range' ],
		'pinned' => [ 'title' => 'Pinned', 'type' => 'flag' ],
		'featured' => [ 'title' => 'Featured', 'type' => 'flag' ],
		'popular' => [ 'title' => 'Popular', 'type' => 'flag' ],
		'anonymous' => [ 'title' => 'Anonymous', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'status' => [
			'approve' => 'Approve', 'spam' => 'Spam', 'trash' => 'Trash', 'block' => 'Block'
		],
		'model' => [
			'pinned' => 'Pinned', 'featured' => 'Featured', 'popular' => 'Popular',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , null, 'x2', 'x2', 'x2', 'x2', null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'parent' => [ 'title' => $parentCol, 'generate' => function( $model ) use( $parentUrl ) {
			$name = $model->name ?? 'View';
			if( !empty( $parentUrl ) ) {
				return "<a href='". Url::toRoute( [ $parentUrl . $model->parentId ], true ). "'>$name</a>";
			}
		}],
		'title' => 'Title',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) {
			return isset( $model->creator ) ? $model->creator->name : null;
		}],
		'name' => 'Name',
		'email' => 'Email',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'pinned' => [ 'title' => 'Pinned', 'generate' => function( $model ) { return $model->getPinnedStr(); } ],
		'featured' => [ 'title' => 'Featured', 'generate' => function( $model ) { return $model->getFeaturedStr(); } ],
		'popular' => [ 'title' => 'Popular', 'generate' => function( $model ) { return $model->getPopularStr(); } ],
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
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
