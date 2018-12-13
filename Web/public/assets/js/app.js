document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    // 切换语言
    var lang = document.getElementById('lang');

    lang.addEventListener('change', function() {
        var lang_code = lang.options[lang.selectedIndex].value;
        Cookies.set('Lang', lang_code, { expires: 7 });
        location.reload();
    });
}); 
