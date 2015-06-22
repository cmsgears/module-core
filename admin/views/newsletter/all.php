<?php
// Yii Imports
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Newsletters';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-newsletter';
$this->params['sidebar-child'] 	= 'newsletter';

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$searchTerms	= Yii::$app->request->getQueryParam("search");

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam("sort");

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Newsletter", ["/cmgcore/newsletter/create"], ['class'=>'btn'] )  ?>				
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
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>					
					<th>Description</th>
					<th>Created on
						<span class='box-icon-sort'>
							<span sort-order='cdate' class="icon-sort <?php if( strcmp( $sortOrder, 'cdate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-cdate' class="icon-sort <?php if( strcmp( $sortOrder, '-cdate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Updated on
						<span class='box-icon-sort'>
							<span sort-order='udate' class="icon-sort <?php if( strcmp( $sortOrder, 'udate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-udate' class="icon-sort <?php if( strcmp( $sortOrder, '-udate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Last Sent on
						<span class='box-icon-sort'>
							<span sort-order='ldate' class="icon-sort <?php if( strcmp( $sortOrder, 'ldate') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-ldate' class="icon-sort <?php if( strcmp( $sortOrder, '-ldate') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $newsletter ) {

						$id 		= $newsletter->id;
						$editUrl	= Html::a( $newsletter->name, ["/cmgcore/newsletter/update?id=$id"] );
				?>
					<tr>
						<td><?= $editUrl ?></td>					
						<td><?= $newsletter->description ?></td>
						<td><?= $newsletter->createdAt ?></td>
						<td><?= $newsletter->modifiedAt ?></td>
						<td><?= $newsletter->lastSentAt ?></td>
						<td>
							<span class="wrap-icon-action"><?= Html::a( "", ["/cmgcore/newsletter/update?id=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action"><?= Html::a( "", ["/cmgcore/newsletter/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
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