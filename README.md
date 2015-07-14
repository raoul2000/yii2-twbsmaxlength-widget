yii2-twbsmaxlength-widget
==========================
The **TwbsMaxlength** widget is a wrapper for the great [Bootstrap Maxlength plugin](http://mimo84.github.io/bootstrap-maxlength/), 
a visual feedback indicator for the *maxlength* attribute. Have a look to the [demo page](http://mimo84.github.io/bootstrap-maxlength/) for more !

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist raoul2000/yii2-twbsmaxlength-widget "*"
```

or add

```
"raoul2000/yii2-twbsmaxlength-widget": "*"
```

to the require section of your `composer.json` file.


Basic Usage
-----------

Using *TwbsMaxlength* widget is easy. 
In the example below, we are attaching the "Bootstrap Maxlength" plugin to an input text control, with a maximum length of 20 characters set by the
**maxlength** HTML5 attribute :

```php
<input type="text" class="form-control" id="txtinput1" name="xyz" maxlength="20" />

<?php
	raoul2000\widget\twbsmaxlength\TwbsMaxlength::widget(['selector' => '#txtinput1']);
?>
```
The user will not be able to enter more than 20 characters in the text input control. 
After the 10th character (hard coded default) is entered by the user, a small alert will appear at the bottom of the control. 


Remember to use the **selector** option only when you need to attach the "Bootstrap Maxlength" plugin to an existing HTML input tag (text or textarea).

ActiveForm
----------

Most of the time, a text or textarea input control is produced by an ActiveForm widget. The *TwbsMaxlength* is of course also able to handle such use case.

```php
<?php 
	use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
	
	$form = ActiveForm::begin(); 

	echo $form->field($model, 'name')
		->textInput(['maxlength' => true])		
		->widget(TwbsMaxlength::className());

	ActiveForm::end();
?>
```

The code above **only works since Yii2 v2.0.3** which includes a feature to automatically set the **maxlength** attribute of an ActiveField textInput
based on the related `string` validation rule ([Read more](http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html#textInput%28%29-detail)...) 

To use it with a *textarea*, simply add the `type`configuration parameter with value `TwbsMaxlength::INPUT_TEXTAREA` : 

```php
<?php 
	use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
	
	$form = ActiveForm::begin(); 

	echo $form->field($model, 'name')
		->textInput(['maxlength' => true])		
		->widget(TwbsMaxlength::className(),['type' => TwbsMaxlength::INPUT_TEXTAREA]);

	ActiveForm::end();
?>
```

### Legacy support

**If you are using Yii2 version prior to 2.0.3** no problem : the  *TwbsMaxlength* widget implements the same kind of feature than Yii2 2.0.3.
If you don't set the *maxlength* HTML5 attribute yourself, the widget will search for this value among the validation rules defined for the model's attribute.
If the rule string is found and if a maximum length is set, it will be used to inject maxlength into the HTML input element. Otherwise the "Bootstrap Maxlength* 
plugin will not be enabled.

Let's see that on an example with the famous *ContactForm* model. We use the [StringValidator](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html)
to define the maximum length of the 'subject' attribute (here it will be set to 10) :

```php
class ContactForm extends Model
{
    public $subject;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
        	['subject','string', 'length'=> [4,10]],
        ];
    }
    // ....
```
 
Now, no need to set the **maxlength** HTML5 attribute because the widget will do it for us. We can simply write :

```php
<?php 
	use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
	
	$form = ActiveForm::begin(); 

	echo $form->field($model, 'subject')->widget(TwbsMaxlength::className());

	ActiveForm::end();
?>
```

And that's it ! Take a look to the input tag created and you'll see that the **maxlength** HTML5 attribute 
has been set to 10, just like defined in the validation rule of the subject attribute.


Plugin Options
--------------
The [Bootstrap Maxlength](https://github.com/mimo84/bootstrap-maxlength/blob/master/README.md) accepts several options to custoomize its behavior. 
These options can be initialized in the **clientOptions** array, when configuring the widget.

```php
<?php
  use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
  
  $form = ActiveForm::begin(); 
  
  echo $form->field($model, 'body')
  	->textinput(['maxlength' => true])
  	->widget(
      TwbsMaxlength::className(), 
      [
          'clientOptions' => [
              'threshold'         => 10,
              'preText'           => 'You have ',
              'separator'         => ' of ',
              'postText'          => ' chars remaining.',
              'warningClass'      => "label label-success",
              'limitReachedClass' => "label label-danger"
      ]
  ]);
  
  ActiveForm::end();
?>
```               
         
For more information on the plugin options, please refer to [bootstrap-maxlength github page](https://github.com/mimo84/bootstrap-maxlength/).

Widget Options
--------------

## textarea
To produce a *textarea* element instead of the classical text input (produced by default), use the **type** option when
configuring your widget.

```php
<?php 
    use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
    
	$form = ActiveForm::begin(); 

	echo $form->field($model, 'body')
		->textinput(['maxlength' => true])
		->widget(
			TwbsMaxlength::className(),
			[
	    		'type' => TwbsMaxlength::INPUT_TEXTAREA
	    	]
	    );

	ActiveForm::end();
?>
```


## Setting the Threshold
The threshold is a numeric value representing the number of characters left before reaching the **maxlength** limitation. 
When the threshold is reached, an alert is displayed to the user to inform her/him that the max length is about to be reached. 
You can set the threshold value option like any other *clientOption* for the plugin.

```php
<?php
  use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
  
  $form = ActiveForm::begin(); 
  
  echo $form->field($model, 'body')
  	->textinput(['maxlength' => true])
  	->widget(
  		TwbsMaxlength::className(), 
      	[
        	'clientOptions' => [ 'threshold' => 10]
      	]
	);
  
  ActiveForm::end();
?>
``` 

*TwbsMaxlength* provide a way to dynamically calculate the threshold value depending on the max length value. This option name is **thresholdPolicy**.
The *TwbsMaxlength* widget includes 3 built-in thresholds policies :

- `TwbsMaxlength::THRESHOLD_HALF`
- `TwbsMaxlength::THRESHOLD_TWO_THIRD` (default)
- `TwbsMaxlength::THRESHOLD_THREE_QUARTERS`
 
In the example below, the alert will be displayed after 3/4 of the total number of character allowed have been entered by the user. So for instance
if the "subject' validation rule set the maximum length to be 40, the alert will show up when 30 characters are entered.

```php
<?php 
    use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
    
	$form = ActiveForm::begin(); 

	echo $form->field($model, 'subject')
		->textinput(['maxlength' => true])
		->widget(
			TwbsMaxlength::className(),
			[
    			'thresholdPolicy' => TwbsMaxlength::THRESHOLD_THREE_QUARTERS,
    		]
    	);

	ActiveForm::end();
?>
```

Note that the *thresholdPolicy* is only available when working with ActiveForms.


License
-------

**yii2-twbsmaxlength-widget** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
