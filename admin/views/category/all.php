<?php
use \Yii;
use yii\helpers\Html; 
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\modules\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | All Categories';
?>
<div class="content-header clearfix">
	<div class="header-actions"> 
		<?= Html::a( "Add Category", ["/core/category/create"], ['class'=>'btn'] )  ?>
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
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
	<div class="wrap-grid">
		<table>
			<thead>
				<tr>
					<th> <input type='checkbox' /> </th>
					<th>Name</th>				
					<th>Description</th>
					<th>Type</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $page as $category ) {

						$id = $category->getId();
				?>
					<tr>
						<td> <input type='checkbox' /> </td>
						<td><?= $category->getName() ?></td>
						<td><?= $category->getDesc() ?></td>
						<td><?= $typeMap[ $category->getType() ] ?></td>
						<td>
							<span class="wrap-icon-action" title="Update Category"><?= Html::a( "", ["/core/category/update?id=$id"], ['class'=>'icon-action icon-action-edit'] ) ?></span>
							<span class="wrap-icon-action" title="Delete Category"><?= Html::a( "", ["/core/category/delete?id=$id"], ['class'=>'icon-action icon-action-delete'] ) ?></span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-footer">
		<div class="text"> <?=CodeGenUtil::getPaginationDetail( $pages, $page, $total ) ?> </div>
		<?= LinkPager::widget( [ 'pagination' => $pages ] ); ?>
	</div>
</div>
<script type="text/javascript">
	initSidebar( "sidebar-category", 0 );
</script>