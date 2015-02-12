**this is a work in progress : use it at tour own risk**

yii2-twbsmaxlength-widget
==========================
The TwbsMaxlength widget is a wrapper for the [Bootstrap Maxlength plugin](http://mimo84.github.io/bootstrap-maxlength/), 
a visual feedback indicator for the maxlength attribute.

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
Using TwbsMaxlength widget is easy. 
In the example below, we attach it to an input text control, with a maximum length of 25 characters :

```php
<input type="text" class="form-control" id="txtinput1" name="xyz" maxlength="25" ></textarea>

<?php
	raoul2000\widget\twbsmaxlength\TwbsMaxlength::widget(['selector' => '#txtinput1']);
?>
```
The user will not be able to enter more than 25 characters in the text input control. By default
after the 10th character is entered, a small alert will appear at the bottom of the control.

Check ouy the complete set of option availabe with the Plugin on the [demo page](http://mimo84.github.io/bootstrap-maxlength/)

It is important to Note that ...

> Bootstrap-Maxlength uses a Twitter Bootstrap label to show a visual feedback to the user about the maximum length of the field where the user is inserting text. Uses the HTML5 attribute "maxlength" to work.

ActiveForm
------

This is fine for something like the example above, but what if we are working with models and attributes to create our form ? Do we have to add the *maxlength* HTML attribute manually on each input ? ...No worries, the TwbsMaxlength Widget is here to help ! 

Most of the time, Yii forms use models, and are created using the *ActiveForm*. Let's have a look to the example below where
the *ContactForm* model is concerned (only the Subject attribute in this case).

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(TwbsMaxlength::className());

	ActiveForm::end();
?>
```

The TwbsMaxlength widget requires the **maxlength** attribute to be defined in the input control. No such attribute
is defined in the above example, so the Widget is going to explore **Validation rules** associated to the *subject* attribute
in the *ContactForm* model definition. It will look for  [StringValidator](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html) and try to extract its *maxlength* value.

So for this example to work, **we must define a string validation rule for attribute subject**

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

Of course another option would have been to explicitly set the *maxlength* attribute value directly in the field definition.

Plugin Options
-----
The [Bootstrap Maxlength](https://github.com/mimo84/bootstrap-maxlength/blob/master/README.md) accepts several options to custoomize its behavior. These options can be initialized in the **clientOptions** array, when configuring the widget.

```php
$form2->field($model, 'body')->widget(TwbsMaxlength::className(), [
	'clientOptions' => [
    	'threshold' => 10,
		'preText' => 'You have ',
		'separator' => ' of ',
		'postText' => ' chars remaining.',
		'warningClass' => "label label-success",
		'limitReachedClass' => "label label-danger"
	]
]);
```                        

Widget Options
-----

## textarea
To produce a *textarea* element instead of the classical test input (produced by default), use the *type* option when
configuring your widget.

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(TwbsMaxlength::className(),[
    	'type' => TwbsMaxlength::INPUT_TEXTAREA
    ]);

	ActiveForm::end();
?>
```
## Threshold
Use the **thresholdPolicy** option to dynamically calculate the value of the **threshold**. The Threshold is a numeric
value representing the number of characters left before reaching the **maxlength** limitation. when the threshold is
defined, the alert is displayed to the user.

In the example below, the alert will be displayed after 3/4 of the total number of character allowed is reached. So for instance
if the "subject' validation rule defined a maximum length to be 40, the alert will show up when 30 characters are entered.

```php
<?php 
	$form = ActiveForm::begin(['id' => 'contact-form']); 

	echo $form->field($model, 'subject')->widget(TwbsMaxlength::className(),[
    	'thresholdPolicy' => TwbsMaxlength::TRESHOLD_THREE_QUARTERS,
    ]);

	ActiveForm::end();
?>
```

For more information on the plugin options, please refer to [bootstrap-maxlength github page](https://github.com/mimo84/bootstrap-maxlength/).

License
-------

**yii2-twbsmaxlength-widget** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
