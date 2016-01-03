<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Update $title";
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update <?= $title ?></div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-dropdown' ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' ) ?> 
    	<?= $form->field( $model, 'icon' ) ?>
    	<?= $form->field( $model, 'htmlOptions' )->textarea() ?>
		<?= $form->field( $model, 'featured' )->checkbox() ?>

		<div class="box-content clearfix">
			<div class="header">Category Avatar</div>
			<?=AvatarUploader::widget( [ 'options' => [ 'id' => 'avatar-dropdown', 'class' => 'file-uploader' ], 'model' => $avatar, 'modelClass' => 'Avatar', 'directory' => 'avatar' ] );?>
		</div>

		<div class="clear filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>