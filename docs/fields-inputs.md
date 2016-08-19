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
- FileField ```extends BaseField```
    - ElfinderFileInput ```use mihaildev\elfinder\InputFile```
- ImageField ```extends FileField```
    - ElfinderImageInput ```extends ElfinderFileInput``` and ```use mihaildev\elfinder\InputFile```
- FileUploadField ```extends FileField``` and ```use mongosoft\file\UploadBehavior```
    - FileUploadInput ```use kartik\file\FileInput```
- ImageUploadField ```extends FileField``` and ```use mongosoft\file\UploadImageBehavior``` and ```use abeautifulsite\SimpleImage;```
    - ImageUploadInput ```use kartik\file\FileInput```
