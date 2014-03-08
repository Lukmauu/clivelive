function reloadPage(){
    location.reload()
}
function searchButton(e){
    e.preventDefault();
    var searchItens = $('.search').find('.hide-my-xs');
    var $t = $(this);
    if (searchItens.is(':visible')) {
        searchItens.slideUp('slow');
        $t.slideDown();
    } else {
        searchItens.slideDown('slow');
        $t.slideUp();
    }
}
$(document)
.on('click', '.search button.first-btn', searchButton);
