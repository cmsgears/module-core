<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Permission';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h1>Update Permission</h1>
		<?php $form = ActiveForm::begin( ['id' => 'frm-permission-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'permission_name' ) ?>
    	<?= $form->field( $model, 'permission_desc' )->textarea() ?>
		
		<h4>Assign Roles</h4>
		<?php 
			$permissionRoles	= $model->getRolesIdList();

			foreach ( $roles as $role ) { 

				if( in_array( $role['id'], $permissionRoles ) ) {
		?>		
					<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" checked /><?=$role['name']?></span>
		<?php 
				}
				else {
		?>
					<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" /><?=$role['name']?></span>
		<?php
				}
			}
		?>	
		<div class="box-filler"></div>
		<?=Html::a( "Back", [ '/cmgcore/permission/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", -1 );
</script>