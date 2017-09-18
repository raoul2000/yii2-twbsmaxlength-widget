<?php
namespace raoul2000\widget\twbsmaxlength;

use yii\web\AssetBundle;

/**
 * @author Raoul
 */
class TwbsMaxlengthAsset extends AssetBundle
{
	public $sourcePath = '@bower/bootstrap-maxlength/src';
	public $depends = [
		'yii\web\JqueryAsset'
	];
	
	/**
	* @var boolean use minified version of bootstrap-maxlength.js
	*/
	public static $minifyJs = false;
	/**
	 * @see \yii\web\AssetBundle::init()
	 */
	public function init()
	{
		$this->js = [
			'bootstrap-maxlength'.( $minifyJs ? '.min.js' : '.js' )
		];
		return parent::init();
	}
}
