$(document).ready(function () {	
    
    $('#formLoginBtnSubmit').click(function(e){
        e.preventDefault();
        formLoginBtnSubmit('/admin/dashboard');
    });
});


function formLoginBtnSubmit(redirect_url) {
  //  $('#formLoginBtnSubmit').click(function(e){
  //          e.preventDefault();
            $.ajax({
                type: "post",
                url: $('#formLogin').attr('action'),
                data: $('#formLogin').serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status === 1) {
                        window.location.href = redirect_url;
                    } else {
                        $('.formLoginErrors').html('<div class="alert alert-error">' + response.message + '</div>');
                    }
                }
            });
   //     });
}

function setCookie(name,value) {
    var Days = 30; 
    var exp  = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString() + ";path=/";
}

