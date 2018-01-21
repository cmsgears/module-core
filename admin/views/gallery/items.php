<?php
// CMG Imports
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Gallery Items | ' . $coreProperties->getSiteTitle();
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<label>Name</label>
							<label><?= $gallery->name ?></label>
						</div>
						<div class="col col2">
							<label>Title</label>
							<label><?= $gallery->title ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Add Item</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<?= ImageUploader::widget([
						'directory' => 'gallery', 'showFields' => true, 'modelClass' => 'File', 'fileLabel' => true,
						'postAction' => true, 'postActionUrl' => "core/gallery/create-item?id=$gallery->id",
						'cmtApp' => 'main', 'cmtController' => 'gallery', 'cmtAction' => 'updateItem'
					])?>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">All Items</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<ul class="slider-slides clearfix">
					<?php
						foreach ( $items as $item ) {

							$id	= $item->id;
					?>
						<li>
							<?= ImageUploader::widget([
								'directory' => 'gallery', 'showFields' => true, 'model' => $item, 'modelClass' => 'File', 'fileLabel' => true,
								'postAction' => true, 'postActionVisible' => true, 'postActionUrl' => "core/gallery/update-item?id=$gallery->id&iid=$item->id",
								'cmtApp' => 'main', 'cmtController' => 'gallery', 'cmtAction' => 'updateItem'
							])?>
						</li>
					<?php
						}
					?>
					</ul>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
