<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Access Matrix';

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
		<?= Html::a( 'Add Role', [ '/cmgcore/role/create' ], [ 'class' => 'btn btn-large' ] ) ?>				
	</div>
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
					<th>Permission</th>
					<th>Roles</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $permission ) {

						$id 		= $permission->id;
						$roles		= $permission->getRolesIdList();
						$apixUrl	= Yii::$app->urlManager->createAbsoluteUrl( "/apix/cmgcore/permission/bind-roles" );
				?>
					<tr id="perm-matrix-<?=$id?>" class="cmt-request" cmt-controller="permission" cmt-action="matrix" action="<?=$apixUrl?>" method="POST" cmt-clear="false">
						<td><?= $permission->name ?></td>
						<td>
							<input type="hidden" name="Binder[binderId]" value="<?=$id?>" />
							<ul class="nav">
								<?php foreach ( $rolesList as $role ) {

									if( in_array( $role['id'], $roles ) ) {
								?>		
										<li><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" checked /><?=$role['name']?></li>
								<?php
									}
									else {
								?>
										<li><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" /><?=$role['name']?></li>
								<?php
									}
								}
								?>
							</ul>
						</td>
						<td>
							<span class="cmt-click" title="Assign Roles">
								<span class="cmti cmti-save"</span>
							</span>
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