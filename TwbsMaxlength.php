<?php
namespace raoul2000\widget\twbsmaxlength;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * TwbsMaxlength is a wrapper for the [Bootstrap Maxlength](http://mimo84.github.io/bootstrap-maxlength/).
 *
 * @author Raoul
 */
class TwbsMaxlength extends InputWidget
{
	const INPUT_TEXT     = 'text';
	const INPUT_TEXTAREA = 'textarea';
	
	/**
	 * Threshold policies
	 */
	const THRESHOLD_HALF = 1;
	const THRESHOLD_TWO_THIRD = 2;
	const THRESHOLD_THREE_QUARTERS = 3;
	
	/**
	 * @var string JQuery selector to attach the maxlength widget to. If this option is set, it is used 
	 * in priority. It must be empty when the widget is used with ActiveField.
	 */
	public $selector;	
    /**
     * @var array the JQuery plugin options for the bootstrap-maxlength plugin.
     * @see https://github.com/mimo84/bootstrap-maxlength/
     */
	public $clientOptions = [];
	/**
	 * @var string the type of input field to generate (default to 'text').
	 */	
	public $type = self::INPUT_TEXT;
	/**
	 * 
	 * @var integer when attached to an ActiveField, use this property to calculate the treshold as a 
	 * part of the max length value. This settings is used only if no treshold is set in the widget options. By default
	 * the threshold is set to 2/3 of the maxlength value.
	 */
	public $thresholdPolicy = self::THRESHOLD_TWO_THIRD;
	
	/**
	 * 
	 * @see \yii\base\Object::init()
	 */
	public function init()
	{
		if( isset($this->selector)) {
			$this->name ='';
		}
		parent::init();
	}

	/**
	 * @see \yii\base\Widget::run()
	 */
	public function run()
	{
		if( isset($this->selector)) {
			$this->registerClientScript();
		}else {
			
			$this->initClientOptions();
			
			if ($this->hasModel()) {
				echo $this->type == self::INPUT_TEXTAREA ?
					   Html::activeTextarea($this->model, $this->attribute, $this->options)
					 : Html::activeTextInput($this->model, $this->attribute, $this->options);
			} else {
				echo $this->type == self::INPUT_TEXTAREA ?
					  Html::textarea($this->name, $this->value, $this->options)
					: Html::textInput($this->name, $this->value, $this->options);
			}	
			
			if(  isset($this->options['maxlength']) && ! empty($this->options['maxlength'])) {
				$this->registerClientScript();
			}
		}
	}
	
    /**
     * Initializes client options
     */
	protected function initClientOptions()
	{
		if(! isset($this->options['maxlength']) ){
			if( $this->hasModel()) {
				
				$this->options['maxlength'] = self::getMaxLength($this->model, Html::getAttributeName($this->attribute));
			}	
		}
		if( !isset($this->clientOptions['threshold']) && $this->options['maxlength'] != null) {
			$this->initThreshold();
		}
	}
	
	/**
	 * Initialize the threshold value for the maxlength plugin.
	 * Depending on the *thresholdPolicy* option, the *maxlength* value will be divided to
	 * get the actual value of the threshold that is used to initialize the plugin.
	 */
	protected function initThreshold()
	{
		switch ($this->thresholdPolicy) {
			case self::THRESHOLD_TWO_THIRD :
				$this->clientOptions['threshold'] = ceil($this->options['maxlength'] / 3);
				break;
			case self::THRESHOLD_THREE_QUARTERS :
				$this->clientOptions['threshold'] =  ceil($this->options['maxlength'] / 4);
				break;
			default:
				$this->clientOptions['threshold'] = ceil($this->options['maxlength'] / 2);
				break;
		}
	}
	/**
	 * Generates and registers javascript to start the plugin.
	 */
	public function registerClientScript()
	{
		$view = $this->getView();
		TwbsMaxlengthAsset::register($view);

		$options = empty($this->clientOptions) ? "{}" : Json::encode($this->clientOptions);
		if( isset($this->selector)) {
			$js = "jQuery(\"{$this->selector}\").maxlength(" . $options . ")";
		} else {
			$js = "jQuery(\"#{$this->options['id']}\").maxlength(" . $options . ")";
		}
		$view->registerJs($js);
	}

	/**
	 * Add the maxlength attribute to an ActiveField.
	 *
	 * The plugin requires that the max number of characters is specified as the HTML5 attribute "maxlength". This
	 * method adds this attribute if it is not already defined into the HTML attributes of an ActiveField. The
	 * value is retrieved from the StringValidator settings that is attached to the model attribute.
	 * Note that if maxlength can't be defined, the plugin is not registred for the view.
	 * 
	 * Note that this method is deprecated and should be replaced by ActiveField widget initialization
	 * as explained in the README file.
	 * 
	 * @deprecated since Yii2 2.0.3 it is possible to set the maxlength HTML attribute of a text input
	 * [Read more](http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html#textInput%28%29-detail)
	 * 
	 * @param yii\widgets\ActiveField $field
	 * @param array $clientOptions Bootstrap maxlength plugin options
	 * @param boolean $render when true, the $field is output
	 * @return yii\widgets\ActiveField the field containing the "maxlength" option (if it could be obtained)
	 */
	public static function apply($field, $clientOptions, $render = true)
	{
		if ( isset($field->inputOptions['maxlength'])) {
			$maxLength = $field->inputOptions['maxlength'];
		} else {
			$maxLength = static::getMaxLength($field->model, Html::getAttributeName($field->attribute));
		}
		if ( ! empty($maxLength) ) {
			$field->inputOptions['maxlength'] = $maxLength;
			$id = Html::getInputId($field->model, $field->attribute);
			static::widget( [
				'selector' => '#'.$id,
				'clientOptions' => $clientOptions
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
