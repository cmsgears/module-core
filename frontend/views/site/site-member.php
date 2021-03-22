<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Role;

use cmsgears\core\common\widgets\ActiveForm;

use cmsgears\widgets\block\BasicBlock;

$coreProperties = $this->context->getCoreProperties();
$images			= Yii::getAlias( '@images' );
$defaultAvatar  = $images . '/avatar-user.png';
$avatar			= $user->getAvatarUrl() !== null ? $user->getAvatarUrl() : $defaultAvatar;

$userRole = Role::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
?>
<?php
	BasicBlock::begin([
		'options' => [ 'id' => 'block-public', 'class' => 'block block-basic' ],
		'bkg' => true, 'bkgUrl' => $bannerUrl
	]);
?>
<div class="row max-cols-100 padding padding-large-h">
	<div class="align align-center">
		<img class="fluid avatar bkg bkg-white circled circled1" src="<?= $avatar ?>">
		<div class="filler-height"></div>
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page', 'options' => [ 'class' => 'form' ] ] ); ?>
			<div class="hidden-easy">
				<input name="SiteMember[siteId]" value="<?= Yii::$app->core->getSiteId() ?>" >
				<input name="SiteMember[userId]" value="<?= $user->id ?>" >
				<input name="SiteMember[roleId]" value="<?= $userRole->id ?>">
			</div>
			<div class="align align-center">
				<input class="element-medium" type="submit" value="Join" />
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
<?php BasicBlock::end(); ?>
