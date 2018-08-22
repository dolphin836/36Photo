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
});