<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Delete File | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete File</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-file' ] );?>

		<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'altText' )->textInput( [ 'readonly' => true ] )?>
		<?= $form->field( $model, 'link' )->textInput( [ 'readonly' => true ] )?>
		<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel',  [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<div class="filler-height"></div>

		<?php ActiveForm::end(); ?>
	</div>
</div>