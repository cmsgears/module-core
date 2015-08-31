<?php
// Yii Imports
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Options';

// Sidebars
$this->params['sidebar-parent'] = 'sidebar-dropdown';
$this->params['sidebar-child'] 	= 'sidebar-dropdown';

// Searching
$searchTerms	= Yii::$app->request->getQueryParam("search");

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam("sort");

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Option", ["dropdown/option/create?id=$category->id"], ['class'=>'btn'] )  ?>				
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
					<th>Name</th>					
					<th>Message</th> 
					<th>Icon</th>
					<th>Actions</th>  
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $option ) {

						$id 		= $option->id;	 
						
				?>
					<tr>
						<td><?= $option->name ?></td> 
						<td><?= $option->message ?></td> 
						<td> <span class="<?= $option->icon ?>" title="<?= $option->name ?>"></span></td> 
						<td>
							<span class="wrap-icon-action" title="Edit Option"><?= Html::a( "", ["dropdown/option/update?id=$id"], ['class'=>'icon-action icon-action-edit'] )  ?></span>								 
							<span class="wrap-icon-action" title="Delete Option"><?= Html::a( "", ["dropdown/option/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] )  ?></span>
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