function transliteration(text) {
// Символ, на который будут заменяться все спецсимволы
    var space = '-';
// Берем значение из нужного поля и переводим в нижний регистр
    var txt = text.toLowerCase();

// Массив для транслитерации
    var transl = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
        'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
        'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': space, 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya',
        ' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
        '#': space, '$': space, '%': space, '^': space, '&': space, '*': space,
        '(': space, ')': space, '-': space, '\=': space, '+': space, '[': space,
        ']': space, '\\': space, '|': space, '/': space, '.': space, ',': space,
        '{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
        '?': space, '<': space, '>': space, '№': space, '–': space,
        '«': space, '»': space
    }

    var result = '';
    var curent_sim = '';

    for (i = 0; i < txt.length; i++) {
        // Если символ найден в массиве то меняем его
        if (transl[txt[i]] != undefined) {
            if (curent_sim != transl[txt[i]] || curent_sim != space) {
                result += transl[txt[i]];
                curent_sim = transl[txt[i]];
            }
        }
        // Если нет, то оставляем так как есть
        else {
            result += txt[i];
            curent_sim = txt[i];
        }
    }

    result = TrimStr(result);

// Выводим результат
    return result;
}

function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
}
