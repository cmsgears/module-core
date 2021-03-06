<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete City | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$region	= isset( $model->region ) ? $model->region->name : null;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-city', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'regionId', [
								'placeholder' => 'Region', 'icon' => 'cmti cmti-search',
								'value' => $region, 'disabled' => true
							])?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'code' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'iso' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'latitude' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'longitude' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'zone' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'regions' )->textarea( [ 'readonly' => true ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'postal' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'zipCodes' )->textarea( [ 'readonly' => true ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Delete" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
