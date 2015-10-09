<?php
// Yii Imports
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Access Matrix";

// Sidebar
$sidebar						= $this->context->sidebar;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( 'Add Role', [ '/cmgcore/role/create' ], [ 'class' => 'btn' ] ) ?>				
	</div>
	<div class="header-search"> 
		<form action="#">
			<input type="text" name="search" />
			<input type="submit" value="Search" />
		</form>
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
					<tr id="perm-matrix-<?=$id?>" class="request-ajax" cmt-controller="permission" cmt-action="matrix" action="<?=$apixUrl?>" method="POST" cmt-clear-data="false">
						<td><?= $permission->name ?></td>
						<td>
							<input type="hidden" name="Binder[binderId]" value="<?=$id?>" />
							<ul class="ul-inline">
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
							<span class="wrap-icon-action cmt-submit" title="Assign Roles" cmt-request="perm-matrix-<?=$id?>">
								<span class="icon-action icon-action-save"</span>
							</span>
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