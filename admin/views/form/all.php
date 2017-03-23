<?php
// Yii Imports
use yii\helpers\Html;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'All Forms | ' . $coreProperties->getSiteTitle();
$siteUrl		= $coreProperties->getSiteUrl();

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$keywords		= Yii::$app->request->getQueryParam( 'keywords' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="row header-content">
	<div class="col-small col15x10 header-actions">
		<span class="frm-icon-element element-small">
			<i class="cmti cmti-plus"></i>
			<?= Html::a( 'Add', [ 'create' ], [ 'class' => 'btn' ] ) ?>
		</span>
	</div>
	<div class="col-small col15x5 header-search">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $keywords ?>">
		<span class="frm-icon-element element-medium">
			<i class="cmti cmti-search"></i>
			<button id="btn-search">Search</button>
		</span>
	</div>
</div>
<div class="row header-content">
	<div class="col col12x8 header-actions">
		<span class="bold">Sort By:</span>
		<span class="wrap-sort">
			<?= $dataProvider->sort->link( 'name', [ 'class' => 'sort btn btn-medium' ] ) ?>
		</span>
	</div>
	<div class="col col12x4 header-actions align align-right">
		<span class="wrap-filters"></span>
	</div>
</div>

<div class="data-grid">
	<div class="row grid-header">
		<div class="col col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col col12x6 pagination">
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
					<th>Template</th>
					<th>Captcha</th>
					<th>Visibility</th>
					<th>User Mail</th>
					<th>Admin Mail</th>
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
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					$slugBase		= $siteUrl;
					$controllerName	= Yii::$app->controller->id;

					foreach( $models as $form ) {

						$id			= $form->id;
						$editUrl	= Html::a( $form->name, [ "update?id=$id" ] );
				?>
					<tr>
						<td><?= $editUrl ?></td>
						<td><?= $form->getTemplateName() ?></td>
						<td><?= $form->getCaptchaStr() ?></td>
						<td><?= $form->getVisibilityStr() ?></td>
						<td><?= $form->getUserMailStr() ?></td>
						<td><?= $form->getAdminMailStr() ?></td>
						<td><?= $form->createdAt ?></td>
						<td><?= $form->modifiedAt ?></td>
						<td>
							<?php if( $submits ) { ?>
								<span title="Submits"><?= Html::a( "", [ "$controllerName/submit/all?fid=$id" ], [ 'class' => 'cmti cmti-checkbox-b-active' ] )	 ?></span>
							<?php } ?>
							<span title="Fields"><?= Html::a( "", [ "$controllerName/field/all?fid=$id" ], [ 'class' => 'cmti cmti-list-small' ] )	?></span>
							<span title="Update"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
							<span title="Delete"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'cmti cmti-close-c-o' ] )  ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="row grid-header">
		<div class="col col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>