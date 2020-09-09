<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= "Update $title | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$stars	= $this->context->stars;
$user	= isset( $model->createdBy ) ? $model->creator->getName() : null;

Editor::widget();
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-comment', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud layer layer-10">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="row row-medium">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'createdBy', [
								'placeholder' => 'Search Creator', 'icon' => 'cmti cmti-search',
								'value' => $user, 'url' => 'core/user/auto-search'
							]) ?>
						</div>
						<div class="note">Notes: Use Search Creator only in case submit need to be done on behalf of selected user.</div>
					</div>
					<div class="filler-height filler-height-medium"></div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'email' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'avatarUrl' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'websiteUrl' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured' ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'anonymous' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Stars</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate1' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate2' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate3' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate4' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate5' ] ) ?>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<p class="note">If stars are set, average value of the stars will be used to set the rating.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="align align-right layer layer-5">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
