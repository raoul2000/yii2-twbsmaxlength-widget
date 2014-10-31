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
Using TwbsMaxlength widget is easy. In the example below, we attach it to an input text control, with a maximum length of 25 :

```php
<?php
use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
?>

<input type="text" class="form-control" id="txtinput1" name="xyz" maxlength="25" ></textarea>

<?php
	TwbsMaxlength::widget([
		'selector' => '#txtinput1',
		'pluginOptions' => [
			'threshold' => 10,
			'placement' => 'top'
		]
	]);
?>
```

You'll find the complete set of option availabe with the Plugin on the [demo page](http://mimo84.github.io/bootstrap-maxlength/)

Advanced Usage
-----

Note that ...

> Bootstrap-Maxlength uses a Twitter Bootstrap label to show a visual feedback to the user about the maximum length of the field where the user is inserting text. Uses the HTML5 attribute "maxlength" to work.

This is fine for something like the example above, but what if we are working with models and attributes to create our form ? 
Do we have to add the *maxlength* HTML attribute manually on each input ? ...No worries, the *TwbsMaxlength* Widget is here to help ! 

**Using yii\helpers\HTML::activeInput**

```php
<?php

	use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
	
	// TwbsMaxlength helps you get the max character number 
	// for your model's attribute.
	
	echo Html::activeInput('text', $model, 'name',
		[
			'maxlength' => TwbsMaxlength::getMaxLength($model, 'name',50),
			'class' => 'form-control'
		]
	);
	
	// and now insert the js plugin
	
	TwbsMaxlength::widget([
		'selector' => '#'.Html::getInputId($model,'name'),
		'pluginOptions' => [
			'threshold' => 30,
			'placement' => 'top'
		]
	]);	
?>
```


**Using yii\helpers\HTML::activeInput**

Another way of working with form and model is to use the *ActiveForm* Widget. Yes, in this case *TwbsMaxlength* is still your friend !
By using **TwbsMaxlength::apply**, you add the *maxlength* HTML attribute to the field, based on the validation rule that
is defined for the model's attribute.

```php
<?php
	use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= $form->field($model, 'subject') ?>
                
                <?= TwbsMaxlength::apply(
						$form->field($model, 'body'),
						['threshold' => 20 ],
						false
					)->textarea(['rows' => 6]) 
				?>
				
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

?>
```

When *TwbsMaxlength* needs to find the value to use for *maxlength*, it searches for the first [StringValidator](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html) related to the 
model's attribute and use the *max* value.


For more information on the plugin options, please refer to [bootstrap-maxlength github page](https://github.com/mimo84/bootstrap-maxlength/).

License
-------

**yii2-twbsmaxlength-widget** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.