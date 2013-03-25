$(function() {


    $('.navigation > li:nth-child(8) > ul').css('margin-left', '-200px');
    hoverMenu();
    activeMenu();
    adjustSubmenuWidth();
    $('#widge ul li:last-child').css('margin-right', '0');
    $('#text_container .contacts_block > ul.toggle > li:nth-child(odd)').css({
        "font-family": "HelveticaNeueBold",
        "font-size": "12px",
        "float": "left",
        "list-style": "none",
        "width": "30%"
    }); 
});

function setCookie(name,value) {
    var Days = 30; 
    var exp  = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString() + ";path=/";
}

function hoverMenu() {
    hoverMenuHelper(1, 'rgb(55,147,26)');
    hoverMenuHelper(2, 'rgb(249,210,6)');
    hoverMenuHelper(3, 'rgb(0,43,148)');
    hoverMenuHelper(4, 'rgb(92,221,255)');
    hoverMenuHelper(5, 'rgb(150,191,13)');
    hoverMenuHelper(6, 'rgb(139,139,137)');
    hoverMenuHelper(7, 'rgb(255,125,0)');
    hoverMenuHelper(8, '#93117e');
}

function hoverMenuHelper(nth_child, rgb) {
    
    $('.navigation > li:nth-child('+ nth_child + '):not(.active)').hover(function() {
        $('.navigation > li ul').hide();
        $('.navigation > li:nth-child('+ nth_child + ') > a').css('border-bottom', '6px solid ' + rgb);
        $('.navigation > li:nth-child('+ nth_child + ') ul').show();
        
    }, function() {
        $('.navigation > li:nth-child('+ nth_child + '):not(.active) >ul').hide();
        $('.navigation > li:nth-child('+ nth_child + ') > a').css('border-bottom', '');
        $('.navigation > li.active > ul').show();
    });

}

function activeMenu() {
    $('.navigation > li:not(.active) > ul').css('display', 'none');
    $('.navigation > li:nth-child(1).active > a').css('border-bottom', '6px solid rgb(55,147,26)');
    $('.navigation > li:nth-child(2).active > a').css('border-bottom', '6px solid rgb(249,210,6)');
    $('.navigation > li:nth-child(3).active > a').css('border-bottom', '6px solid rgb(0,43,148)');
    $('.navigation > li:nth-child(4).active > a').css('border-bottom', '6px solid rgb(92,221,255)');
    $('.navigation > li:nth-child(5).active > a').css('border-bottom', '6px solid rgb(150,191,13)');
    $('.navigation > li:nth-child(6).active > a').css('border-bottom', '6px solid rgb(139,139,137)');
    $('.navigation > li:nth-child(7).active > a').css('border-bottom', '6px solid rgb(255,125,0)');
    $('.navigation > li:nth-child(8).active > a').css('border-bottom', '6px solid #93117e');
}

function adjustSubmenuWidth() {
    $('.navigation > li:nth-child(1) > ul').css('width', '650px');
    $('.navigation > li:nth-child(2) > ul').css('width', '780px');
    $('.navigation > li:nth-child(3) > ul').css('width', '700px');
    $('.navigation > li:nth-child(4) > ul').css('width', '500px');
    $('.navigation > li:nth-child(5) > ul').css('width', '400px');
    $('.navigation > li:nth-child(7) > ul').css('width', '250px');
    $('.navigation > li:nth-child(8) > ul').css('width', '320px');
}
