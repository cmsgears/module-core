<?php
// Yii Imports
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | All Categories";

// Sidebar
$sidebar						= $this->context->sidebar;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( 'search' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Category", [ 'create' ], [ 'class' => 'btn' ] )  ?>				
	</div>
	<div class="header-search">
		<input type="text" name="search" id="search-terms" value="<?php if( isset($searchTerms) ) echo $searchTerms;?>">
		<input type="submit" name="submit-search" value="Search" onclick="return searchTable();" />
	</div>
</div>
<div class="data-grid">
	<div class="grid-header">
		<?= LinkPager::widget( [ 'pagination' => $pagination ] ); ?>
	</div>
	<div class="wrap-grid">
		<table>
			<thead>
				<tr>
					<th>Avatar</th>
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Parent
						<span class='box-icon-sort'>
							<span sort-order='parent' class="icon-sort <?php if( strcmp( $sortOrder, 'parent') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-parent' class="icon-sort <?php if( strcmp( $sortOrder, '-parent') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Description</th> 
					<th>Featured</th>
					<th>Icon</th>
					<th>Actions</th>  
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $category ) {

						$id 		= $category->id;
				?>
					<tr>
						<td><?= CodeGenUtil::getImageThumbTag( $category->avatar, [ 'class' => 'avatar', 'image' => 'avatar.png' ] ) ?></td>
						<td><?= $category->name ?></td>
						<td><?= $category->getParentName() ?></td>
						<td><?= $category->description ?></td>
						<td><?= $category->getFeaturedStr() ?></td> 
						<td> <span class="<?= $category->icon ?>" title="<?= $category->name ?>"></span></td> 
						<td>
							<span class="wrap-icon-action" title="Edit Category"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'icon-action icon-action-edit'] )  ?></span>	 
							<span class="wrap-icon-action" title="Delete Category"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'icon-action icon-action-delete'] )  ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-footer">
		<div class="text"> <?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?> </div>
		<?= LinkPager::widget( [ 'pagination' => $pagination ] ); ?>
	</div>
</div> 