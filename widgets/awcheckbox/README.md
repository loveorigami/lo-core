Yii2 widget - Awesome Bootstrap Checkbox
==========================
This yii2 extension is a wrapper for the [Awesome Bootstrap Checkbox & Radios plugin](https://github.com/flatlogic/awesome-bootstrap-checkbox)


## Requires
This extension require [awesome-bootstrap-checkbox](https://github.com/flatlogic/awesome-bootstrap-checkbox), [yii2-bootstrap](http://www.yiiframework.com/doc-2.0/ext-bootstrap-index.html) and [font-awesome](https://fortawesome.github.io/Font-Awesome/)

## Usage

```php
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

// ActiveForm & model - default checkbox
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname());

// ActiveForm & model - change type to radio
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'type'=>AwesomeCheckbox::TYPE_RADIO, // default type AwesomeCheckbox::TYPE_CHECKBOX
]);

// ActiveForm & model - change style (you can use STYLE_DEFAULT, STYLE_PRIMARY, STYLE_SUCCESS, STYLE_INFO, STYLE_WARNING, STYLE_DANGER - it is bootstrap colors)
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'style'=>AwesomeCheckbox::STYLE_SUCCESS,
]);

// ActiveForm & model - circle checkbox with style
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'type'=>AwesomeCheckbox::TYPE_CHECKBOX, //optional string default type TYPE_CHECKBOX
    'style'=>[
        AwesomeCheckbox::STYLE_CIRCLE,
        AwesomeCheckbox::STYLE_SUCCESS
    ],
]);

// ActiveForm & model - checkbox list
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'type'=>AwesomeCheckbox::TYPE_CHECKBOX, //optional string default type TYPE_CHECKBOX
    'style'=>AwesomeCheckbox::STYLE_PRIMARY,
    'list'=>[ // array data
        'id1'=>'item1',
        'id2'=>'item2'
    ]
]);

// ActiveForm & model - radio list
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'style'=>AwesomeCheckbox::STYLE_PRIMARY,
    'list'=>[ // array data
        'id1'=>'item1',
        'id2'=>'item2'
    ]
]);

// ActiveForm & model - disabled option
echo $form->field($model, 'attribute')->widget(AwesomeCheckbox::classname(),[
    'options'=>[
        'disabled'=>true
    ]
]);



// By name - default checkbox
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - default radio
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - change style 
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'style'=>AwesomeCheckbox::STYLE_PRIMARY,
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - circle checkbox with style
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'style'=>[AwesomeCheckbox::STYLE_CIRCLE, AwesomeCheckbox::STYLE_PRIMARY],
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - checked
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'checked' => true,
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - disabled
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'checked' => true,
    'options'=>[
        'disabled'=>true,
        'label'=>'Checkbox'
    ]
]);

// By name - list checkbox
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'list'=>[ // array data
        'id1'=>'item1',
        'id2'=>'item2'
    ],
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - list radio
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'type'=>AwesomeCheckbox::TYPE_RADIO,
    'list'=>[ // array data
        'id1'=>'item1',
        'id2'=>'item2'
    ],    
    'options'=>[
        'label'=>'Checkbox'
    ]
]);

// By name - checked list checkbox
echo AwesomeCheckbox::widget([
    'name'=>'test',
    'checked' => 'id1', // you can use string or array with values for list
    'list'=>[ // array data
        'id1'=>'item1',
        'id2'=>'item2'
    ],
    'options'=>[
        'label'=>'Checkbox'
    ]
]);
```

