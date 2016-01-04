<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Form Field | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Form Field</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-form-field' ] );?>

		<?= $form->field( $model, 'name' ) ?>
		<?= $form->field( $model, 'label' ) ?>
		<?= $form->field( $model, 'type' )->dropDownList( $typeMap ) ?>
		<?= $form->field( $model, 'compress' )->checkbox() ?>
		<?= $form->field( $model, 'validators' ) ?>
		<?= $form->field( $model, 'htmlOptions' )->textarea() ?>

		<div class="clear filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>