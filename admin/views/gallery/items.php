<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Gallery Items | ' . $coreProperties->getSiteTitle();
$id				= $gallery->id;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Gallery Items</div>
	</div>
	<div class="box-wrap-content clearfix">

		<div class="box-content">
			<div class="header">Gallery Details</div>
			<div class="info">
				<table>
					<tr><td>Name</td><td><?=$gallery->name?></td></tr>
					<tr><td>Title</td><td><?=$gallery->title?></td></tr>
				</table>
			</div>
		</div>

		<div class="box-content frm-split-40-60 clearfix">
			<div class="header">Create Item</div>
			<?= ImageUploader::widget([
					'options' => [ 'id' => 'gallery-item', 'class' => 'file-uploader' ],
					'directory' => 'gallery', 'info' => true,
					'postAction' => 'true', 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
					'postActionUrl' => "core/gallery/create-item?id=$id"
			]); ?>
		</div>

		<div class="box-content frm-split-40-60 clearfix">
			<div class="header">All Items</div>
			<ul class="slider-slides clearfix">
			<?php
				foreach ( $items as $item ) {

					$id	= $item->id;
			?>
				<li>
					<?= ImageUploader::widget([
							'options' => [ 'id' => "item-update-$id", 'class' => 'file-uploader' ],
							'directory' => 'gallery', 'info' => true, 'model' => $item,
							'postAction' => 'true', 'postActionId' => "frm-item-update-$id", 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
							'postActionVisible' => true, 'postActionUrl' => "core/gallery/update-item?id=$id"
					]); ?>
				</li>
			<?php
				}
			?>
			</ul>
		</div>
	</div>
</div>