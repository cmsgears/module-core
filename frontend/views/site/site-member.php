<?php
// Yii Imports
use yii\widgets\ActiveForm;

// CMG Imports
use cmsgears\widgets\block\BasicBlock;

use cmsgears\cms\common\utilities\ContentUtil;

$coreProperties = $this->context->getCoreProperties();
$pageInfo		= ContentUtil::getPageInfo( $this );
$bannerUrl		= $pageInfo[ 'content' ]->getBannerUrl();
$images			= Yii::getAlias( "@images" );
$defaultAvatar  = $images . '/avatar-user.png';
$avatar			= $user->getAvatarUrl() !== null ? $user->getAvatarUrl() : $defaultAvatar ;
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
		<h3><?= $pageInfo[ 'content' ]['content'] ?></h3>
		<div class="filler-height"></div>
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page', 'options' => [ 'class' => 'form' ] ] ); ?>
			<div class="hidden-easy">
				<input name="SiteMember[siteId]" value="<?= Yii::$app->core->getSiteId()?>" >
				<input name="SiteMember[userId]" value="<?= $user->id ?>" >
				<input name="SiteMember[roleId]" value="3" >
			</div>
			<div class="align align-center">
				<input class="element-medium" type="submit" value="Allow" />
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>	


<?php BasicBlock::end(); ?>
