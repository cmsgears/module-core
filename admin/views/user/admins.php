<?php
// Yii Imports
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | All Admins";

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
		<?= Html::a( "Add User", ["/cmgcore/user/create"], ['class'=>'btn'] )  ?>				
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
					<th>Avatar</th>
					<th>Username
						<span class='box-icon-sort'>
							<span sort-order='username' class="icon-sort <?php if( strcmp( $sortOrder, 'username') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-username' class="icon-sort <?php if( strcmp( $sortOrder, '-username') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Name
						<span class='box-icon-sort'>
							<span sort-order='name' class="icon-sort <?php if( strcmp( $sortOrder, 'name') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-name' class="icon-sort <?php if( strcmp( $sortOrder, '-name') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Email
						<span class='box-icon-sort'>
							<span sort-order='email' class="icon-sort <?php if( strcmp( $sortOrder, 'email') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-email' class="icon-sort <?php if( strcmp( $sortOrder, '-email') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Role
						<span class='box-icon-sort'>
							<span sort-order='role' class="icon-sort <?php if( strcmp( $sortOrder, 'role') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-role' class="icon-sort <?php if( strcmp( $sortOrder, '-role') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Status
						<span class='box-icon-sort'>
							<span sort-order='status' class="icon-sort <?php if( strcmp( $sortOrder, 'status') == 0 ) echo 'icon-up-active'; else echo 'icon-up';?>"></span>
							<span sort-order='-status' class="icon-sort <?php if( strcmp( $sortOrder, '-status') == 0 ) echo 'icon-down-active'; else echo 'icon-down';?>"></span>
						</span>
					</th>
					<th>Mobile</th>
					<th>Reg Date</th>
					<th>Last Login</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $user ) {

						$id 	= $user->id;
						$role	= $user->role->name;
				?>
					<tr>
						<td> 
							<?php
								$avatar = $user->avatar;

								if( isset( $avatar ) ) { 
							?> 
								<img class="avatar" src="<?= $avatar->getThumbUrl() ?>">
							<?php 
								} else { 
							?>
								<img class="avatar" src="<?=Yii::getAlias('@images')?>/avatar.png">
							<?php } ?>
						</td>
						<td><?= $user->username ?></td>
						<td><?= $user->getName() ?></td>
						<td><?= $user->email ?></td>
						<td><?= $role ?></td>
						<td><?= $user->statusStr ?></td>
						<td><?= $user->phone ?></td>
						<td><?= $user->registeredAt ?></td>
						<td><?= $user->lastLoginAt ?></td>
						<td>
							<span class="wrap-icon-action"><?= Html::a( "", ["/cmgcore/user/update?id=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action"><?= Html::a( "", ["/cmgcore/user/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
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
<script type="text/javascript">
	initSidebar( "sidebar-identity", 3 );
</script>