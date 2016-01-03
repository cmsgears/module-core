<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Gallery';
$returnUrl		= $this->context->returnUrl;
$id				= $gallery->id;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Gallery Items</div>
	</div>
	<div class="box-wrap-content clearfix">

		<div class="box-content frm-split-40-60 clearfix">
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
			<?= FileUploader::widget( [ 'options' => [ 'id' => 'gallery-item', 'class' => 'file-uploader frm-split' ],
					'directory' => 'gallery', 'infoFields' => true, 
					'postAction' => 'true', 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
					'postActionUrl' => "/apix/cmgcore/gallery/create-item?id=$id" 
			]); ?>
		</div>

		<div class="box-content frm-split-40-60 clearfix">
			<div class="header">All Items</div>
			<ul class="slider-slides clearfix">
			<?php
				foreach ( $items as $item ) {
	
					$id		= $item->id;
			?>
				<li>
					<?= FileUploader::widget( [ 'options' => [ 'id' => "item-update-$id", 'class' => 'file-uploader frm-split' ],
							'directory' => 'gallery', 'infoFields' => true, 'model' => $item,
							'postAction' => 'true', 'postActionId' => "frm-item-update-$id", 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
							'postActionVisible' => true, 'postActionUrl' => "/apix/cmgcore/gallery/update-item?id=$id"
					]); ?>
				</li>
			<?php
				}
			?>
			</ul>
		</div>
	</div>
</div>