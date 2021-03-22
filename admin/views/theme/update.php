<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Theme | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$renderers		= Yii::$app->templateManager->renderers;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-theme', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
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
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'default' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'renderer' )->dropDownList( $renderers, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'basePath' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
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
