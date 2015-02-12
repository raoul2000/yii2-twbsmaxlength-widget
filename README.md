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
-----
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
After the 10th character is entered by the user, a small alert will appear at the bottom of the control. By default the **threshold** option is set to the half of the maximum number of characters.

Remember to use the **selector** option only when you need to attache the "Bootstrap Maxlength" plugin to an existing
HTML input tag (text or textarea).

The "Bootstrap Maxlength" plugin accepts a complete set of options to customize its behavior. Check the [Github Project page](http://mimo84.github.io/bootstrap-maxlength/) !

ActiveForm
------
The plugin uses the HTML5 **maxlength** attribute to get the maximum number of characters a user can enter into a 
text, or a textarea input control.

That being said, using the widget with an [ActiveForm](http://www.yiiframework.com/doc-2.0/yii-widgets-activeform.html) and an [ActiveField](http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html) is simple : 

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(
		raoul2000\widget\twbsmaxlength\TwbsMaxlength::className(),
		[ 
			'options' => ['maxlength' => 10]
		]
	);

	ActiveForm::end();
?>
```

Normally, if the plugin doesn't find any *maxlength* attribute, it is not initialized, it not active, nothing happens (that's sad).

... but wait ! The TwbsMaxlength Widget is not going to give up so easely. Maybe there is already a maxlength constraint configured somewhere ! ... and the best place
to search is among the validation rules that may have been defined for the model attribute (of course).

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
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(
		raoul2000\widget\twbsmaxlength\TwbsMaxlength::className()
	);

	ActiveForm::end();
?>
```

And that's it ! Take a look to the input tag created and you'll see that the **maxlength** HTML5 attribute 
has been set to 10, just like defined in the validation rule of the subject attribute.

So remember that if you don't set the **maxlength** HTML5 attribute yourself, the widget will search for this value among the validation rules defined for the model's attribute. If the rule **string** is found and if a maximum length is set, it will be used to inject **maxlength** into the HTML input element. Otherwise the "Bootstrap Maxlength* plugin will not be enabled.


Plugin Options
-----
The [Bootstrap Maxlength](https://github.com/mimo84/bootstrap-maxlength/blob/master/README.md) accepts several options to custoomize its behavior. These options can be initialized in the **clientOptions** array, when configuring the widget.

```php
<?php
  $form = ActiveForm::begin(['id' => 'contact-form']); 
  
  echo $form->field($model, 'body')->widget(
      raoul2000\widget\twbsmaxlength\TwbsMaxlength::className(), 
      [
          'clientOptions' => [
              'threshold' => 10,
              'preText' => 'You have ',
              'separator' => ' of ',
              'postText' => ' chars remaining.',
              'warningClass' => "label label-success",
              'limitReachedClass' => "label label-danger"
      ]
  ]);
  
  ActiveForm::end();
?>
```                        

Widget Options
-----

## textarea
To produce a *textarea* element instead of the classical test input (produced by default), use the **type** option when
configuring your widget.

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(
		raoul2000\widget\twbsmaxlength\TwbsMaxlength::className(),
		[
    		'type' => raoul2000\widget\twbsmaxlength\TwbsMaxlength::INPUT_TEXTAREA
    	]
    );

	ActiveForm::end();
?>
```
## Threshold
Use the **thresholdPolicy** option to dynamically calculate the value of the **threshold**. The Threshold is a numeric
value representing the number of characters left before reaching the **maxlength** limitation. When the threshold is reached, 
an alert is displayed to the user.

In the example below, the alert will be displayed after 3/4 of the total number of character allowed have been entered by the user. So for instance
if the "subject' validation rule set the maximum length to be 40, the alert will show up when 30 characters are entered.

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(
		raoul2000\widget\twbsmaxlength\TwbsMaxlength::className(),
		[
    		'thresholdPolicy' => raoul2000\widget\twbsmaxlength\TwbsMaxlength::THRESHOLD_THREE_QUARTERS,
    	]
    );

	ActiveForm::end();
?>
```

For more information on the plugin options, please refer to [bootstrap-maxlength github page](https://github.com/mimo84/bootstrap-maxlength/).

License
-------

**yii2-twbsmaxlength-widget** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.