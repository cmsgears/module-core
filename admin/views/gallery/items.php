<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Gallery Items';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];

$id				= $gallery->id;
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Gallery Items</h2>
		<form action="#" class="frm-split">
			<label>Name</label>
			<label><?=$gallery->name?></label>
			<label>Title</label>
			<label><?=$gallery->title?></label>
		</form>

		<h4>Create Item</h4>
		<?= FileUploader::widget( [ 'options' => [ 'id' => 'gallery-item', 'class' => 'file-uploader' ],
				'directory' => 'gallery', 'infoFields' => true, 
				'postAction' => 'true', 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
				'postActionUrl' => "/apix/cmgcore/gallery/create-item?id=$id", 
				'btnChooserIcon' => 'icon-action icon-action-edit' ] );
		?>

		<h4>All Items</h4>
		
		<ul class="slider-slides clearfix">
		<?php
			foreach ( $items as $item ) {

				$id		= $item->id;
		?>
			<li>
				<?= FileUploader::widget( [ 'options' => [ 'id' => "item-update-$id", 'class' => 'file-uploader' ],
						'directory' => 'gallery', 'infoFields' => true, 'model' => $item,
						'postAction' => 'true', 'postActionId' => "frm-item-update-$id", 'cmtController' => 'gallery', 'cmtAction' => 'updateItem',
						'postActionVisible' => true, 'postActionUrl' => "/apix/cmgcore/gallery/update-item?id=$id",
						'btnChooserIcon' => 'icon-action icon-action-edit' ] );
				?>
			</li>
		<?php
			}
		?>
		</ul>
	</div>
</section>