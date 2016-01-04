<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\FileUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Site | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Site</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site' ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'order' ) ?> 

		<div class="box-content clearfix">
			<div class="header">Site Avatar</div>
			<?= AvatarUploader::widget( [ 'options' => [ 'id' => 'avatar-listing', 'class' => 'file-uploader' ], 'model' => $avatar, 'modelClass' => 'Avatar', 'directory' => 'avatar' ] );?>
		</div>
		
		<div class="box-content clearfix">
			<div class="header">Site Banner</div>
			<?= FileUploader::widget( [ 'options' => [ 'id' => 'banner-listing', 'class' => 'file-uploader' ], 'model' => $banner, 'modelClass' => 'Banner', 'directory' => 'banner' ] );?>
		</div>

		<div class="filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>