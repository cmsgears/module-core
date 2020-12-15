<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\files\widgets\SharedUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add File | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-file', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<label>File</label>
							<?= SharedUploader::widget( [ 'model' => $model ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'caption' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'link' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'altText' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
