<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$title			= ucfirst( $this->context->commentUrl );
$coreProperties = $this->context->getCoreProperties();
$this->title	= "All $title | " . $coreProperties->getSiteTitle();

$parentUrl 		= $this->context->parentUrl;

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
			<?php if( isset( $model ) ) { ?>
				<i class="cmti cmti-plus"></i>
				<?= Html::a( 'Add', [ "create?pid=$model->id" ], [ 'class' => 'btn' ] ) ?>
			<?php } ?>
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
					<th>Email
						<span class='box-icon-sort'>
							<span sort-order='slug' class="icon-sort <?php if( strcmp( $sortOrder, 'slug') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-slug' class="icon-sort <?php if( strcmp( $sortOrder, '-slug') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Status</th>
					<?php if( $parent ) { ?>
						<th>Parent</th>
					<?php } ?>
					<th>Message
						<span class='box-icon-sort'>
							<span sort-order='title' class="icon-sort <?php if( strcmp( $sortOrder, 'title') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-title' class="icon-sort <?php if( strcmp( $sortOrder, '-title') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $comment ) {

						$id			= $comment->id;
						$parentId	= $comment->parentId;
						$editUrl	= Html::a( $comment->name, [ "update?id=$id" ] );
						$content	= CodeGenUtil::getSummary( $comment->content );
				?>
					<tr>
						<td><?= $editUrl ?></td>
						<td><?= $comment->email ?></td>
						<td><?= $comment->getStatusStr() ?></td>
						<?php if( $parent ) { ?>
							<td><a href="<?= Url::toRoute( [ $parentUrl . $parentId ], true ) ?>">parent</a></td>
						<?php } ?>
						<td><?= $content ?></td>
						<td class="actions">
							<span title="Update"><?= Html::a( "", [ "update?id=$id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
							<span title="Delete"><?= Html::a( "", [ "delete?id=$id" ], [ 'class' => 'cmti cmti-close-c-o' ] )  ?></span>
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