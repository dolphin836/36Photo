$(document).ready(function() {
    var search_start = $("#search_start"),
        search_end   = $('#search_end'),
        search       = $("#search");
    // DateTimePicker
    if (search_start.length > 0 || search_end.length > 0) {
        $.datetimepicker.setLocale('zh');
    }
    
    var months = [
        '1 月', '2 月',  '3 月',  '4 月',
        '5 月', '6 月',  '7 月',  '8 月',
        '9 月', '10 月', '11 月', '12月'
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
        var search = window.location.search;
        var inputs = $("input[name^='search_'],select[name^='search_']");
        var sort   = $("#sort").val();
        var order  = $("#order").val();
        var query  = '';
        var inputName = new Array();


        inputs.each(function() {
            var name  = $(this).attr("name");
            var value = $(this).val();

            inputName.push(name);

            if (value != '' && value != -1) {
                if (query.length !== 0) {
                    query += "&";
                }

                query += name + "=" + encodeURIComponent(value);
            }            
        });

        if (sort && order) {
            if (query.length !== 0) {
                query += "&";
            }

            query += "sort=" + sort + "&order=" + order;
        }

        if (search != '') {
            search         = search.substring(1);
            var searchItem = search.split("&");

            for (const item of searchItem) {
                var record = item.split("=", 2);

                if (inputName.indexOf(record[0]) == -1) {
                    if (query.indexOf(record[0]) == -1) {
                        if (query.length !== 0) {
                            query += "&";
                        }
    
                        query += record[0] + "=" + encodeURIComponent(record[1]);
                    }
                }
            }
        }

        if (query.length !== 0) {
            window.location.href = path + "?" + query;
        }
    });
    // 操作反馈
    var note = $("#note")

    if (note.length > 0) {
        var secondHtml = $("#second");
        var second = 2;

        var clock = setInterval(() => {
            secondHtml.text(second);

            second--;

            if (second < 0) {
                note.alert('close');
                clearTimeout(clock);
            }
        }, 1000);
    }
});