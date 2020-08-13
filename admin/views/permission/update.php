<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Permission | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-permission', 'options' => [ 'class' => 'form' ] ] ); ?>
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
							<?= $form->field( $model, 'slug' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'group', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'group-permission' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud group-permission">
			<div class="box-header">
				<div class="box-header-title">Group <?= ucfirst( $type ) ?> Permissions</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row rp-map padding padding-small-v">
					<?php
						$children = $model->getChildrenIdList();

						foreach( $permissions as $permission ) {
					?>
							<div class="col col4">
					<?php
							if( in_array( $permission[ 'id' ], $children ) ) {
					?>
								<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" checked /><?= $permission[ 'name' ] ?>
					<?php
							}
							else {
					?>
								<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" /><?= $permission[ 'name' ] ?>
					<?php
							}
					?>
							</div>
					<?php
						}
					?>
					</div>
				</div>
			</div>
		</div>
		<?php if( count( $spermissions ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud group-permission">
				<div class="box-header">
					<div class="box-header-title">Group System Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row rp-map padding padding-small-v">
						<?php
							$children = $model->getChildrenIdList();

							foreach( $spermissions as $permission ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $permission[ 'id' ], $children ) ) {
						?>
									<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" checked /><?= $permission[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" /><?= $permission[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if( count( $roles ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assign Roles</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row rp-map padding padding-small-v">
						<?php
							$modelRoles	= $model->getRolesIdList();

							foreach( $roles as $role ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $role[ 'id' ], $modelRoles ) ) {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $role[ 'id' ] ?>" checked /><?= $role[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $role[ 'id' ] ?>" /><?= $role[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
