# Fields and Inputs

Field (lo\core\db\fields)  Input (lo\core\inputs)

- PkField  
- NumberField ```extends BaseField```
    - NumberInput ```use lo\core\widgets\FormattedNumberInput```
    - NumberSpinInput ```use kartik\touchspin\TouchSpin```
    - RatingInput ```use kartik\touchspin\TouchSpin```
- TimestampField ```extends BaseField```
    - DateRangeInput ```use kartik\date\DatePicker```
- DateField ```extends BaseField```
    - DateInput ```use kartik\date\DatePicker```
- HiddenField ```extends BaseField```
    - HiddenInput
- TextField ```extends BaseField```
    - TextInput
- EmailField ```extends TextField```
- PasswordField ```extends TextField```
    - PasswordInput
- HashField ```extends TextField``` and ```use lo\core\behaviors\HashText```
    - ReadOnlyInput
- SlugField ```extends TextField``` and ```use Zelenin\yii\behaviors\Slug```
    - SlugInput
    - TranslitInput ```use lo\core\widgets\translit\TranslitInput``` for RU translitiration
- TextAreaField ```extends TextField```
    - TextAreaInput
- HtmlField ```extends TextAreaField```
    - CKEditorInput ```use mihaildev\ckeditor\CKEditor```
    - TinyMceInput ```use milano\tinymce\TinyMce```
- FileField 
- ImageField
    - ElfinderImageField
