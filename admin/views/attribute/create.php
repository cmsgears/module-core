<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\icons\widgets\IconChooser;
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title 	= "Add $title | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget();
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-attribute', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'name' ) ?>
							<p class="note">Attribute name must be lower case. An underscore(_) must be used to separate words.</p>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'label' ) ?>
							<p class="note">Attribute label will be used for display purpose.</p>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'valueType' )->dropDownList( $typeMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<?= $form->field( $model, 'type' ) ?>
							<p class="note reader">Attributes can be segregated based on type. It must be set to <em class="bold">default</em> in case no segregation is required.</p>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-1"></div>
		<div class="box box-crud layer layer-2">
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
