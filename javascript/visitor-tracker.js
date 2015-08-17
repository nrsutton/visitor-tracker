var maxDepth = 0;
(function($)
{
    // Check the depth value every second
    window.setInterval("checkDepth()", 1000);
})(jQuery);

function checkDepth()
{
    var docHeight = $( document ).height();
    var distance = $(document).scrollTop();
    var winHeight = $( window ).height();
    var scrollDepth = parseInt( ( distance / ( docHeight - winHeight ) ) * 100 );
    if( scrollDepth > maxDepth )
    {
        maxDepth = scrollDepth;
        vt_createCookie( 'vt_sd', maxDepth, 1 );
    }
}

function vt_createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

function vt_readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
