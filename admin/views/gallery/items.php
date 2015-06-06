<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Gallery Items';

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
		<form class="frm-split frm-ajax" id="frm-item-create" group="1005" key="1001" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl("apix/cmgcore/gallery/create-item?id=$id"); ?>" method="post">
			<?=FileUploader::widget( [ 'options' => [ 'id' => 'gallery-item', 'class' => 'file-uploader' ], 'directory' => 'gallery', 'infoFields' => true ] );?>
			<!-- submit -->
            <input type="submit" class="" value="Create">
			<!-- spinner and success -->
			<div class="frm-spinner"></div>
			<div class="frm-message"></div>
		</form>

		<h4>All Items</h4>
		
		<ul class="slider-slides clearfix">
		<?php
			foreach ( $items as $item ) {

				$itemImage	= $item->file;
				$fileId		= $itemImage->id;
		?>
			<li>
				<form class="frm-ajax" group="1005" key="1001" id="frm-item-update-<?=$fileId?>" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl("apix/cmgcore/gallery/update-item?id=$fileId"); ?>" method="post" keepData="true" >

					<?=FileUploader::widget( [ 'options' => [ 'id' => "file-slide-$fileId", 'class' => 'file-uploader' ], 'model' => $itemImage, 'directory' => 'gallery', 'infoFields' => true ] );?>

					<!-- submit -->
		            <input type="submit" class="" value="Update">
					<!-- spinner and success -->
					<div class="frm-spinner"></div>
					<div class="frm-message"></div>
				</form>
			</li>
		<?php
			}
		?>
		</ul>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-gallery", -1 );
</script>