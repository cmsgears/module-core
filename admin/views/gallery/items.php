<?php
// CMG Imports
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Gallery Items | ' . $coreProperties->getSiteTitle();
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php if( isset( $parent ) ) { ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Parent Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content">
					<div class="row frm-split-40-60">
						<div class="col col2">
							<div class="element-40 bold">Name</div>
							<div class="element-60"><?= $parent->name ?></div>
						</div>
						<div class="col col2">
							<div class="element-40 bold">Title</div>
							<div class="element-60"><?= $parent->title ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content">
					<div class="row frm-split-40-60">
						<div class="col col2">
							<div class="element-40 bold">Name</div>
							<div class="element-60"><?= $gallery->name ?></div>
						</div>
						<div class="col col2">
							<div class="element-40 bold">Title</div>
							<div class="element-60"><?= $gallery->title ?></div>
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
							<form cmt-app="main" cmt-controller="gallery" cmt-action="deleteItem" action="core/gallery/delete-item?id=<?= $gallery->id ?>&iid=<?= $item->id ?>" >
								<div class="max-area-cover spinner">
									<div class="valign-center cmti cmti-spinner-1 spin"></div>
								</div>
								<div class="frm-actions align align-center">
									<input class="element-medium" type="submit" value="Delete" />
								</div>
							</form>
							<div class="filler-height"></div>
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
