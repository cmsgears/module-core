<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title 	= "Delete $title | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-attribute', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'name' )->textInput( [ 'readonly'=> 'true' ] ) ?>
							<p class="note">Attribute name must be lower case. An underscore(_) must be used to separate words.</p>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'label' )->textInput( [ 'readonly'=> 'true' ] ) ?>
							<p class="note">Attribute label will be used for display purpose.</p>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'valueType' )->dropDownList( $typeMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'type' )->textInput( [ 'readonly'=> 'true' ] ) ?>
							<p class="note reader">Attributes can be segregated based on type. It must be set to <em class="bold">default</em> in case no segregation is required.</p>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'disabled' => true, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Value</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'value' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
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
