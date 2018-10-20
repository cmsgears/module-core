<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\SharedUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete File | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-file', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<label>File</label>
							<?= SharedUploader::widget( [ 'model' => $model, 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'altText' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'link' )->textInput( [ 'readonly' => true ] ) ?>
						</div>
						<div class="col col2 render-file">

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
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
