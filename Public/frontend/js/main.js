// Search
function submit_form_search() {
    var keyword = $('#keyword').val();
    
    if(!keyword){
        alert('Bạn phải nhập từ khóa tìm kiếm');
        return false;
    }

    key = $("#keyword").val();
    url = $('#form-search').attr('action') + "&key=" + key;
    window.location.replace(url);

    return false;
}

$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        submit_form_search();
    }
});

$('#keyword').keyup(function() {
    var keywords = $(this).val();

    if(keywords != '') {
        $.ajax({
            url: "index.php?controller=home&action=autocomplete",
            type: "POST",
            async: true,
            data: {keywords: keywords},
            success: function(data) {
                $('.search-list').fadeIn();
                $('.search-list').html(data);
            }
        });
    } else {
        $(".search-list").fadeOut();
    }
});

$('#keyword').click(function() {
    var keywords = $(this).val();

    if(keywords != '') {
        $.ajax({
            url: "index.php?controller=home&action=autocomplete",
            type: "POST",
            data: {keywords: keywords},
            success: function(data) {
                $('.search-list').fadeIn(200);
                $('.search-list').html(data);

            }
        });
    }
})

$(document).click(function(e) {
    if (!$(e.target).is("#keyword")) {
        $(".search-list").fadeOut(200);
        $(".search-list").empty();
    }
});


// $(document).on('click', '.li_search_ajax', function() {
//     $('#keyword').val($(this).text());
//     $('#search_ajax').fadeOut();
// });


