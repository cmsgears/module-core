<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete permission | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
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
							
							<?= $form->field( $model, 'name' ) ?>
							
						</div>
						
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ], 'disabled' => true ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							
							<?= $form->field( $model, 'description' )->textarea() ?>
		
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'group' )->checkbox() ?>
						</div>		
					</div>
					
				</div>
			</div>
		</div>
		<div class="filler-height"> </div>
		<?php if( count( $roles ) > 0 ) { ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title"> Assign Roles </div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row padding padding-small-v">
						<?php foreach ( $roles as $role ) { ?>
							<div class="col col2"><input type="checkbox" name="Binder[bindedData][]" value="<?= $role['id'] ?>" /><?= $role['name'] ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="filler-height"> </div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>

