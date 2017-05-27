<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\SharedUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Add File | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add File</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-file' ] );?>

			<?= SharedUploader::widget([
					'options' => [ 'id' => 'model-file', 'class' => 'file-uploader' ],
					'model' => $model
			]) ?>

		<div class="filler-height"></div>

		<?= $form->field( $model, 'title' ) ?>
		<?= $form->field( $model, 'altText' ) ?>
		<?= $form->field( $model, 'link' ) ?>
		<?= $form->field( $model, 'description' )->textarea() ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel',  [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<div class="filler-height"></div>

		<?php ActiveForm::end(); ?>
	</div>
</div>