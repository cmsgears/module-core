<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Gallery Items';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-gallery';
$this->params['sidebar-child'] 	= 'gallery';

$id				= $gallery->id;
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Gallery Items</h2>
		<form action="#" class="frm-split">
			<label>Name</label>
			<label><?=$gallery->name?></label>
			<label>Description</label>
			<label><?=$gallery->description?></label>
		</form>

		<h4>Create Item</h4>
		<?=FileUploader::widget( [ 'options' => 
				[ 'id' => 'gallery-item', 'class' => 'file-uploader' ],
				'directory' => 'gallery', 'infoFields' => true, 
				'postaction' => 'true', 'cmtcontroller' => 'gallery', 'cmtaction' => 'updateItem',
				'postactionurl' => Yii::$app->urlManager->createAbsoluteUrl("apix/cmgcore/gallery/create-item?id=$id"), 
				'btnChooserIcon' => 'icon-action icon-action-edit' ] );
		?>

		<h4>All Items</h4>
		
		<ul class="slider-slides clearfix">
		<?php
			foreach ( $items as $item ) {

				$id		= $item->id;
		?>
			<li>
				<?=FileUploader::widget( [ 'options' => 
						[ 'id' => "item-update-$id", 'class' => 'file-uploader' ], 
						'directory' => 'gallery', 'infoFields' => true, 'model' => $item,
						'postaction' => 'true', 'postactionid' => "frm-item-update-$id", 'cmtcontroller' => 'gallery', 'cmtaction' => 'updateItem', 'postactionvisible' => true,
						'postactionurl' => Yii::$app->urlManager->createAbsoluteUrl("apix/cmgcore/gallery/update-item?id=$id"), 
						'btnChooserIcon' => 'icon-action icon-action-edit' ] );
				?>
			</li>
		<?php
			}
		?>
		</ul>
	</div>
</section>