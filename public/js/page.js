function showWait(buttonid, sanduhrid) {
    var button = document.getElementById(buttonid);
    button.style.display = "none";
    var sanduhr = document.getElementById(sanduhrid);
    sanduhr.style.display = "block";


    var progressbar = document.getElementById(sanduhrid + "_img");
    if (progressbar) {
        progressbar.src = progressbar.src;
    }

}

function stopShowWait(buttonid, sanduhrid) {
    var button = document.getElementById(buttonid);
    button.style.display = "block";
    var sanduhr = document.getElementById(sanduhrid);
    sanduhr.style.display = "none";
}


function showHelp(ev, mode, id) {
    var box = document.getElementById(id);
    if (mode) {
        var posx = 0;
        var posy = 0;
        if (typeof window.pageYOffset != 'undefined') {
            // Firefox
            posx = ev.layerX;
            posy = ev.layerY;
        } else {
            // IE
            posx = ev.x;
            posy = ev.y;
        }
        box.style.left = posx - 100 + "px";
        box.style.top = posy + 10 + "px";
        box.style.display = "block";
    } else {
        box.style.display = "none";
    }
}

function CheckEnter(event, name) {
    if (event.keyCode == 13) {
        document.f_directsearch.searchtype.value = name;
        if (CheckSearchFormular())
            document.f_directsearch.submit();
        return false;
    }
    return true;
}

function silentAddToBasketBasic(urlparam, cnt) {
    var link = "ajouterPanier?" + urlparam;
    shopwindow = window.open (link,'panier',"display: none");
    //shopwindow.style.display = "none";
    shopwindow.close();

    //window.location.reload();

    var spannode = document.getElementById("CountArticles");
    if (spannode) {
        var textnode = spannode.childNodes[0];
        if (textnode) {
            var data = parseInt(textnode.data) + parseInt(cnt);
            textnode.data = data;
        }
    }
}

function silentAddToBasket(code,nom) {
    var qty = document.getElementById('input-'+code).value;
    var urlparam = "code=" + code + "&desc=" + nom + "&qty=" + qty;
    silentAddToBasketBasic (urlparam,1);
}

function openWait()
{
	show('waitContent');
	hide('pageContent');

	return true;
}

function openWaitAndSubmit()
{
	var ret = openWait();

	document.f_basket.submit();

	return ret;
}

function BasketSubmit (value)
{
	if ( value == 1 ){
        document.f_basket.action.value = 'order';
        return true;
    }
    else if ( value == 2 ) {
        document.f_basket.action.value = 'check';
        document.f_basket.submit();
        return false;
    }

    //document.f_basket.submit();

}

function createAlert(message){
    $.confirm({
        title: '<br/> '+message,
        content: '',
        draggable: false,
        buttons: {
            cancel:{
                text: 'OK',
                btnClass: 'btn-stauff',
                keys: ['enter'],
            },
        }
    });
}
