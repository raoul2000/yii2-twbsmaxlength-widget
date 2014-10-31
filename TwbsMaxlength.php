<?php
namespace raoul2000\widget\twbsmaxlength;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * TwbsMaxlength is a wrapper for the [Bootstrap Maxlength](http://mimo84.github.io/bootstrap-maxlength/).
 *
 * @author Raoul
 */
class TwbsMaxlength extends Widget
{

	/**
	 * @var string JQuery selector to attach the maxlength widget to.
	 */
	public $selector;

	/**
	 * @var array Bootstrap maxlength plugin options
	 */
	public $pluginOptions = [];

	/**
	 * Checks that a value is provided for the "selector" attribute.
	 * @see \yii\base\Object::init()
	 */
	public function init()
	{
		parent::init();
		if (empty($this->selector)) {
			throw new InvalidConfigException('The "selector" property must be set.');
		}
	}

	/**
	 * @see \yii\base\Widget::run()
	 */
	public function run()
	{
		$this->registerClientScript();
	}

	/**
	 * Generates and registers javascript to start the plugin.
	 */
	public function registerClientScript()
	{
		$view = $this->getView();
		TwbsMaxlengthAsset::register($view);

		$options = empty($this->pluginOptions) ? "{}" : Json::encode($this->pluginOptions);
		$js = "jQuery(\"{$this->selector}\").maxlength(" . $options . ")";
		$view->registerJs($js);
	}

	/**
	 * Add the maxlength attribute to an ActiveField.
	 *
	 * The plugin requires that the max number of characters is specified as the HTML5 attribute "maxlength". This
	 * method adds this attribute if it is not already defined, into the HTML attributes of an ActiveField. The
	 * value is retrieved from the StringValidator settings that could be attached to the model attribute.
	 * Note that if maxlength can be defined, the plugin is not registred for the view.
	 *
	 * @param yii\widgets\ActiveField $field
	 * @param array $pluginOptions Bootstrap maxlength plugin options
	 * @param boolean $render when true, the $field is output
	 * @return yii\widgets\ActiveField the field containing the "maxlength" option (if it could be obtained)
	 */
	public static function apply($field, $pluginOptions, $render = true)
	{
		if ( isset($field->inputOptions['maxlength'])) {
			$maxLength = $field->inputOptions['maxlength'];
		} else {
			$maxLength = static::getMaxLength($field->model, $field->attribute);
		}
		if ( ! empty($maxLength) ) {
			$field->inputOptions['maxlength'] = $maxLength;
			$id = Html::getInputId($field->model, $field->attribute);
			static::widget( [
				'selector' => '#'.$id,
				'pluginOptions' => $pluginOptions
			]);
		}
		if ( $render ) {
			echo $field;
		}
		return $field;
	}

	/**
	 * Find the maxlength parameter for an attribute's model.
	 *
	 * This method searches for a yii\validators\StringValidator among all the active validators (based on the current
	 * model scenario). If it founds one, it returns the max length parameter value. If no such value can be found because it is not
	 * defined or because no StringValidator is active, $defaultValue is returned.
	 *
	 * @param yii\base\Model $model
	 * @param string $attribute the attribute name
	 * @return integer | null the maxlength setting
	 * @see yii\validators\StringValidator
	 */
	public static function getMaxLength($model, $attribute, $defaultValue = null)
	{
		$maxLength = null;
		foreach ($model->getActiveValidators($attribute) as $validator) {
			if ( $validator instanceof yii\validators\StringValidator) {
				$maxLength = $validator->max;
				break;
			}
		}
		return $maxLength !== null ? $maxLength : $defaultValue;
	}
}
