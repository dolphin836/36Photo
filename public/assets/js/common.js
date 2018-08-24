$(document).ready(function() {
    var search_start = $("#search_start"),
        search_end = $('#search_end'),
        search = $("#search"),
        remove = $(".remove"),
        action = $("#action-switch"),
        ant = $(".ant");
    // DateTimePicker
    $.datetimepicker.setLocale('zh');

    var months = [
        '1 月', '2 月', '3 月', '4 月',
        '5 月', '6 月', '7 月', '8 月',
        '9 月', '10 月', '11 月', '12月',
    ];

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
    // 删除
    remove.click(function() {
        $.confirm({
            title: '删除确认',
            content: '您确定要删除这条记录吗？',
            theme: 'modern',
            buttons: {
                No: {
                    text: '取消',
                    keys: ['esc']
                },
                Yes: {
                    text: '确定',
                    btnClass: 'btn-blue',
                    keys: ['enter'],
                    action: function() {
                        window.location.reload();
                    }
                }
            }
        });
    });
    // 操作列
    action.click(function() {
        ant.toggleClass("d-none");
        ant.toggleClass("d-block");
    });
});