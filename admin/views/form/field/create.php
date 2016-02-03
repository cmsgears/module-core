<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Form Field | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Form Field</div>
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

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>