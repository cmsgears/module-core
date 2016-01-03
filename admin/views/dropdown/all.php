<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | All $title";

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
		<?= Html::a( "Add $title", [ 'create' ], [ 'class' => 'btn btn-medium' ] ) ?>				
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
					<th>Avatar</th>
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
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

					foreach( $models as $model ) {

						$id = $model->id;	 
						
				?>
					<tr>
						<td><?= CodeGenUtil::getImageThumbTag( $model->avatar, [ 'class' => 'avatar', 'image' => 'avatar.png' ] ) ?></td>
						<td><?= $model->name ?></td>
						<td><?= $model->description ?></td>
						<td><?= $model->getFeaturedStr() ?></td> 
						<td> <span class="<?= $model->icon ?>" title="<?= $model->name ?>"></span></td> 
						<td>
							<?php if( $title == 'Dropdown' ) { ?>						
								<span title="View Options"><?= Html::a( "", [ "/cmgcore/dropdown/option/all?id=$id" ], [ 'class' => 'cmti cmti-list-small' ] )  ?></span>
							<?php } ?>
							<?php if( $title == 'Checkbox Group' ) { ?>						
								<span title="View Options"><?= Html::a( "", [ "/cmgcore/checkboxgroup/option/all?id=$id" ], [ 'class' => 'cmti cmti-list-small' ] )  ?></span>
							<?php } ?>
							<span title="Update <?= $title ?>"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
							<span title="Delete <?= $title ?>"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'cmti cmti-close-o-b' ] )  ?></span>
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