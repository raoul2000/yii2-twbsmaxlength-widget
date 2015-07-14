<?php
namespace raoul2000\widget\twbsmaxlength;

use yii\web\AssetBundle;

/**
 * @author Raoul
 */
class TwbsMaxlengthAsset extends AssetBundle
{
	public $sourcePath = '@bower/bootstrap-maxlength';
	public $depends = [
		'yii\web\JqueryAsset'
	];
	/**
	 * @see \yii\web\AssetBundle::init()
	 */
	public function init()
	{
		$this->js = [
			'bootstrap-maxlength'.( YII_ENV_DEV ? '.js' : '.min.js' )
		];
		return parent::init();
	}
}
