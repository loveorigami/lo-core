# Fields and Inputs

Field (lo\core\db\fields)  Input (lo\core\inputs)

- PkField  
- TextField 
    - TextInput
- SlugField ```extends TextField```
    - SlugInput
    - TranslitInput ```use lo\core\widgets\translit\TranslitInput``` for RU translitiration
- TextAreaField ```extends TextField```
    - TextAreaInput
- HtmlField 
    - CKEditorInput
- FileField 
- ImageField
    - ElfinderImageField
