<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'All Templates | ' . $coreProperties->getSiteTitle();

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( 'search' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="header-content clearfix">
	<div class="header-actions col15x10">
		<?= Html::a( 'Add Template', [ 'create' ], [ 'class' => 'btn btn-medium' ] ) ?>				
	</div>
	<div class="header-search col15x5">
		<input id="search-terms" class="field-large" type="text" name="search" value="<?= $searchTerms ?>">
		<span class="frm-icon-element field-small">
			<i class="cmti cmti-search"></i>
			<button id="btn-search" class="btn btn-small">Search</button>
		</span>
	</div>
</div>

<div class="data-grid">
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
	<div class="grid-content">
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
					<th>Layout</th>
					<th>View Path</th>
					<th>Admin View</th>
					<th>Frontend View</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $template ) {

						$id = $template->id;	 
						
				?>
					<tr>
						<td><?= $template->name ?></td>
						<td><?= $template->description ?></td>
						<td><?= $template->layout ?></td>
						<td><?= $template->viewPath ?></td>
						<td><?= $template->adminView ?></td>
						<td><?= $template->frontendView ?></td>
						<td>
							<span title="Update Template"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
							<span title="Delete Template"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'cmti cmti-close-o-b' ] )  ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>