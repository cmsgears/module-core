<?php
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Forms';
$siteUrl		= $coreProperties->getSiteUrl();

// Sidebar
$sidebar						= $this->context->sidebar;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

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
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( 'Add Form', [ 'create' ], [ 'class' => 'btn' ] )  ?>				
	</div>
	<div class="header-search">
		<input type="text" name="search" id="search-terms" value="<?php if( isset( $searchTerms ) ) echo $searchTerms;?>">
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

						$id 		= $form->id;
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
							<span class="wrap-icon-action" title="View Submits"><?= Html::a( "", ["$controllerName/submit/all?formid=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action" title="View Fields"><?= Html::a( "", ["$controllerName/field/all?formid=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action" title="Update Form"><?= Html::a( "", ["update?id=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>
							<span class="wrap-icon-action" title="Delete Form"><?= Html::a( "", ["delete?id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
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