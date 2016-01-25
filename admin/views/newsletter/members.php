<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Members';

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
	<div class="header-actions col15x10"></div>
	<div class="header-search col15x5">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $searchTerms ?>">
		<span class="frm-icon-element element-small">
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
					<th>Email
						<span class='box-icon-sort'>
							<span sort-order='email' class="icon-sort <?php if( strcmp( $sortOrder, 'email') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-email' class="icon-sort <?php if( strcmp( $sortOrder, '-email') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Name</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $member ) {

						$id 	= $member->id;
				?>
					<tr>
						<td><?= $member->email ?></td>
						<td><?= $member->name ?></td>
						<td><?= $member->getActiveStr() ?></td>
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