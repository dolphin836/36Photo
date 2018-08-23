$(document).ready(function() {
    // 检索
    $("#filter_submit").click(function() {
        var path   = window.location.pathname;
        var filter = $("input[name^='filter_'],select[name^='filter_']");
        var is     = true;

        filter.each(function() {
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
    $(".btn-delete").click(function() {
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
});