<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Site | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col3">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'slug' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'themeId' )->dropDownList( $themesMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'order' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Files</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row padding padding-small-v">
						<div class="col col2">
							<label>Avatar</label>
							<?= AvatarUploader::widget( [ 'model' => $avatar ] ) ?>
						</div>
						<div class="col col2">
							<label>Banner</label>
							<?= ImageUploader::widget( [ 'model' => $banner ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
