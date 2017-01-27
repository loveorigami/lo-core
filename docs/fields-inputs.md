# Fields and Inputs

Field (lo\core\db\fields)  Input (lo\core\inputs)
## One fields
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
- FileField ```extends BaseField```
    - ElfinderFileInput ```use mihaildev\elfinder\InputFile```
- ImageField ```extends FileField```
    - ElfinderImageInput ```extends ElfinderFileInput``` and ```use mihaildev\elfinder\InputFile```
- FileUploadField ```extends FileField``` and ```use mongosoft\file\UploadBehavior```
    - FileUploadInput ```use kartik\file\FileInput```
- ImageUploadField ```extends FileField``` and ```use mongosoft\file\UploadImageBehavior``` and ```use claviska\SimpleImage;```
    - ImageUploadInput ```use kartik\file\FileInput```
- CheckBoxField ```extends BaseField```
    - CheckBoxInput
    - CheckBoxInputA ```use lo\core\widgets\awcheckbox\AwesomeCheckbox```
    - CheckBoxInputB ```use lo\widgets\Toggle```


## Many Fields 
- ListField ```extends BaseField```
    - DropDownInput
- HasOneField ```extends ListField``` for relation data
    - DropDownInput
    - Select2Input ```use kartik\select2\Select2```
- ManyManyField
    - Select2MultiInput ```use kartik\select2\Select2```
    - CheckBoxListInput ```use lo\core\widgets\awcheckbox\AwesomeCheckbox```