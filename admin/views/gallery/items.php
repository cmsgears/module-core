<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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
			<div id="file-slide" class="file-container" legend="Gallery Image" selector="gallery" utype="image" btn-class="btn file-input-wrap" btn-text="Choose Image">
				<div class="file-fields">
					<input type="hidden" name="File[name]" class= "file-name" />
					<input type="hidden" name="File[extension]" class= "file-extension" />
					<input type="hidden" name="File[directory]" value="gallery" />
					<input type="hidden" name="File[changed]" class="file-change" />
					<label>Image Description</label> <input type="text" name="File[description]" />
					<label>Image Alternate Text</label> <input type="text" name="File[altText]" />
					<label>Image Link</label> <input type="text" name="File[link]" />
				</div>
			</div>
			<!-- submit -->
            <input type="submit" class="" value="Create">
			<!-- spinner and success -->
			<div class="spinner"></div>
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

					<div id="file-slide-<?=$fileId?>" class="file-container" legend="Gallery Image" selector="gallery" utype="image" btn-class="btn file-input-wrap" btn-text="Change Image">
						<div class="file-fields">
							<input type="hidden" name="File[id]" value="<?php if( isset( $itemImage ) ) echo $itemImage->id; ?>" />
							<input type="hidden" name="File[name]" class="file-name" value="<?php if( isset( $itemImage ) ) echo $itemImage->name; ?>" />
							<input type="hidden" name="File[extension]" class="file-extension" value="<?php if( isset( $itemImage ) ) echo $itemImage->extension; ?>" />
							<input type="hidden" name="File[directory]" value="gallery" />
							<input type="hidden" name="File[changed]" class="file-change" />
							<label>Image Description</label> <input type="text" name="File[description]" value="<?php if( isset( $itemImage ) ) echo $itemImage->description; ?>" />
							<label>Image Alternate Text</label> <input type="text" name="File[altText]" value="<?php if( isset( $itemImage ) ) echo $itemImage->altText; ?>" />
							<label>Image Link</label> <input type="text" name="File[link]" value="<?php if( isset( $itemImage ) ) echo $itemImage->link; ?>" />
						</div>
					</div>
			
					<!-- submit -->
		            <input type="submit" class="" value="Update">
					<!-- spinner and success -->
					<div class="spinner"></div>
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
	initFileUploader();

<?php 
	foreach ( $items as $item ) {

		$itemImage	= $item->file;
		$fileId		= $itemImage->id;

		if( isset( $item ) ) {

			$itemImageUrl	= $itemImage->getFileUrl(); 
			$image 			= "<img src='$itemImageUrl' />";
?>

			jQuery("#frm-item-update-<?=$fileId?> .file-image").html( "<?= $image ?>" );

<?php 
		}
	}
?>
</script>