# Fields and Inputs

Field (lo\core\db\fields)  Input (lo\core\inputs)

- PkField  
- NumberField ```extends BaseField```
    - NumberInput ```use lo\core\widgets\FormattedNumberInput```
    - NumberSpinInput ```use kartik\touchspin\TouchSpin```
- TextField 
    - TextInput
- SlugField ```extends TextField```
    - SlugInput
    - TranslitInput ```use lo\core\widgets\translit\TranslitInput``` for RU translitiration
- TextAreaField ```extends TextField```
    - TextAreaInput
- HtmlField 
    - CKEditorInput ```use mihaildev\ckeditor\CKEditor```
    - TinyMceInput ```use milano\tinymce\TinyMce```
- FileField 
- ImageField
    - ElfinderImageField
