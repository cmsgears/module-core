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
$this->params['sidebar-parent'] = 'sidebar-identity';
$this->params['sidebar-child'] 	= 'matrix';

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Role", ["/cmgcore/role/create"], ['class'=>'btn'] )  ?>				
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
					<tr>
						<td><?= $permission->name ?></td>
						<td>
							<form action="<?=$apixUrl?>" method="POST">
								<input type="hidden" name="Binder[binderId]" value="<?=$id?>" />
								<ul class="ul-inline">
									<?php foreach ( $rolesList as $role ) { 
										
										if( in_array( $role['id'], $roles ) ) {
									?>		
											<li><input type="checkbox" name="Binder[bindedData]" value="<?=$role['id']?>" checked /><?=$role['name']?></li>
									<?php		
										}
										else {
									?>
											<li><input type="checkbox" name="Binder[bindedData]" value="<?=$role['id']?>" /><?=$role['name']?></li>
									<?php
										}
									}
									?>
								</ul>
							</form>
						</td>
						<td><span class="wrap-icon-action" title="Assign Roles"><span class="icon-action icon-action-save matrix-row"</span></span></td>
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
<script type="text/javascript">
	initMappingsMatrix();
</script>