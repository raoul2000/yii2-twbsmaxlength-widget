<?php
namespace raoul2000\widget\twbsmaxlength;

use yii\web\AssetBundle;

/**
 * @author Raoul <raoul.boulard@gmail.com>
 */
class TwbsMaxlengthAsset extends AssetBundle
{

	public $depends = [
		'yii\web\JqueryAsset'
	];
	/**
	 * @see \yii\web\AssetBundle::init()
	 */
	public function init()
	{
		$this->sourcePath = __DIR__.'/assets';
		$this->js = [
			'bootstrap-maxlength'.( YII_ENV_DEV ? '.js' : '.min.js' )
		];
		return parent::init();
	}
}
