<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\files\widgets\SharedUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update File | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$fileErrors = $file->getErrors();
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
							<?= SharedUploader::widget( [ 'model' => $file ] ) ?>
							<?php if( count( $fileErrors ) > 0 && isset( $fileErrors[ 'name' ] ) ) { ?>
								<p class="error">File is required.</p>
							<?php } ?>
						</div>
						<div class="col col2">
							<?= $form->field( $file, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $file, 'title' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $file, 'caption' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $file, 'link' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $file, 'altText' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col4">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active' ) ?>
						</div>
						<div class="col col4">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned' ) ?>
						</div>
						<div class="col col4">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured' ) ?>
						</div>
						<div class="col col4">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'popular' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
