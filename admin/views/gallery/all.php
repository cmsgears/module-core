<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Galleries';

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( "search" );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( "sort" );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Gallery", ['/cmgcore/gallery/create'], ['class'=>'btn'] )  ?>				
	</div>
	<div class="header-search">
		<input type="text" name="search" id="search-terms" value="<?php if( isset($searchTerms) ) echo $searchTerms;?>">
		<input type="submit" name="submit-search" value="Search" onclick="return searchTable();" />
	</div>
</div>
<div class="data-grid">
	<div class="grid-header">
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
	<div class="wrap-grid">
		<table>
			<thead>
				<tr>
					<th> <input type='checkbox' /> </th>
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>					
					<th>Description</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $page as $gallery ) {

						$id = $gallery->id;
				?>
					<tr>
						<td> <input type='checkbox' /> </td>
						<td><?= $gallery->name ?></td>
						<td><?= $gallery->description ?></td>
						<td>
							<span class="wrap-icon-action" title="Gallery Items"><?= Html::a( "", ["/cmgcore/gallery/items?id=$id"], ['class'=>'icon-action icon-action-edit'] ) ?></span>
							<span class="wrap-icon-action" title="Update Role"><?= Html::a( "", ["/cmgcore/gallery/update?id=$id"], ['class'=>'icon-action icon-action-edit'] ) ?></span>
							<span class="wrap-icon-action" title="Delete Role"><?= Html::a( "", ["/cmgcore/gallery/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] ) ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-footer">
		<div class="text"> <?=CodeGenUtil::getPaginationDetail( $pages, $page, $total ) ?> </div>
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
</div>
<script type="text/javascript">
	initSidebar( "sidebar-gallery", -1 );
</script>