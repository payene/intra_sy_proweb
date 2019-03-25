function pagineOneTable(tableId) {
    var rows = $('#' + tableId).find('tbody tr').length;
    var no_rec_per_page = 10;
    var no_pages = Math.ceil(rows / no_rec_per_page);
    var $pagenumbers = $('<div id="pages" > Pages </div>');
    for (i = 0; i < no_pages; i++)
    {
        $('<span class="page">' + (i + 1) + '</span>').appendTo($pagenumbers);
    }
    $pagenumbers.insertAfter('#' + tableId);
    $('.page').hover(
            function () {
                $(this).addClass('hover');
            },
            function () {
                $(this).removeClass('hover');
            },
            function () {
                $(this).css('background-color', 'white');
            }
    );
    $('#' + tableId).find('tbody tr').hide();
    var tr = $('#' + tableId + ' tbody tr');
    for (var i = 0; i <= no_rec_per_page - 1; i++)
    {
        $(tr[i]).show();
    }
    $('span').click(function (event) {
        console.log('fghghfg');
        $('#' + tableId +'').find('tbody tr').hide();
        for (i = ($(this).text() - 1) * no_rec_per_page; i <= $(this).text() * no_rec_per_page - 1; i++)
        {
            $(tr[i]).show();
        }
        $('.page').css('background-color', 'white');
        $(this).css('background-color', 'orange');
    });
}




function pagine() {
    var rows = $('#tablePage').find('tbody tr').length;
    var no_rec_per_page = 10;
    var no_pages = Math.ceil(rows / no_rec_per_page);
    var $pagenumbers = $('<div id="pages" > Pages </div>');
    for (i = 0; i < no_pages; i++)
    {
        $('<span class="page sy-page">' + (i + 1) + '</span>').appendTo($pagenumbers);
    }
    $pagenumbers.insertAfter('#tablePage');
    $('.sy-page').hover(
            function () {
                $(this).addClass('hover');
            },
            function () {
                $(this).removeClass('hover');
            },
            function () {
                $(this).css('background-color', 'white');
            }
    );
    $('#tablePage').find('tbody tr').hide();
    var tr = $('#tablePage tbody tr');
    for (var i = 0; i <= no_rec_per_page - 1; i++)
    {
        $(tr[i]).show();
    }
    $('span.page.sy-page').click(function (event) {
        // alert('fghghfg');
        $('#tablePage').find('tbody tr').hide();
        for (i = ($(this).text() - 1) * no_rec_per_page; i <= $(this).text() * no_rec_per_page - 1; i++)
        {
            $(tr[i]).show();
        }
        $('.page').css('background-color', 'white');
        $(this).css('background-color', 'orange');
    });
}
