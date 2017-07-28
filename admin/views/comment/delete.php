
<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;
$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Create Block | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true ] );

$modelClass		= $this->context->modelService->getModelClass();
$statusMap		= $modelClass::$statusMap;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-block', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
								<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
		
						</div>
						
						<div class="col col2">
							<?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>
		
						</div>
					</div>
					<div class="filler-height"> </div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'status')->dropDownList( $statusMap , [ 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
						</div>		
					</div>
					<div class="filler-height"> </div>
					<div class="row">
						<div class="col col1">
							<?= $form->field( $model, 'content' )->textarea(['readonly' => true , 'class' => 'content-editor' ]) ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
