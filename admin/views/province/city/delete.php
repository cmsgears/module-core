<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Delete City | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete City</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-city' ] ); ?>

		<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'zone' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'latitude' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'longitude' )->textInput( [ 'readonly' => true ] ) ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>