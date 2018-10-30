$(document).ready(function() {
    var search_start = $("#search_start"),
        search_end = $('#search_end'),
        search = $("#search");
    // DateTimePicker
    if (search_start.length > 0 || search_end.length > 0) {
        $.datetimepicker.setLocale('zh');
    }
    
    var months = [
        '1 月', '2 月', '3 月', '4 月',
        '5 月', '6 月', '7 月', '8 月',
        '9 月', '10 月', '11 月', '12月',
    ];

    if (search_start.length > 0) {
        search_start.datetimepicker({
            i18n:{
                zh:{
                    months: months
                }
            },
            format: 'Y-m-d H:i:s',
            onShow: function() {
                this.setOptions({
                    maxDate: search_end.val() ? search_end.val() : false
                })
            },
            closeOnDateSelect: true
        });
    }

    if (search_end.length > 0) {
        search_end.datetimepicker({
            i18n:{
                zh:{
                    months: months
                }
            },
            format: 'Y-m-d H:i:s',
            onShow: function() {
                this.setOptions({
                    minDate: search_start.val() ? search_start.val() : false
                })
            },
            closeOnDateSelect: true
        });
    }
    // 检索
    search.click(function() {
        var path   = window.location.pathname;
        var inputs = $("input[name^='search_'],select[name^='search_']");
        var is     = true;

        inputs.each(function() {
            var name  = $(this).attr("name");
            var value = $(this).val();

            if (value != '' && value != -1) {
                if (path.substr(-1, 1) != '?' && is) {
                    path += "?";
                }

                if (is) {
                    is    = false;
                } else {
                    path += "&";
                }

                path += name + "=" + encodeURIComponent(value);
            }
        });

        if (path != window.location.pathname) {
            window.location.href = path;
        }
    });
});