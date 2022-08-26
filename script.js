function $Ajax(url, method, data, ondone, heades = []) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    if (method == "POST" && data.isArray) {
        let formData = new FormData();
        data.forEach((key, value) => {
            formData.append(key, value);
        });
        data = formData;
    }

    xhr.send(data);

    heades.forEach((key, value) => {
        xhr.setRequestHeader();
    });

    xhr.onload = () => { ondone(xhr.response, xhr); }




}

const $TO = setTimeout;

const $Q = function (selector, a = false) {
    if (a) { return document.querySelectorAll(selector); }
    return document.querySelector(selector);

}

window.notytimer = 0;

function Notyfy($text, error = false) {
    clearTimeout(window.notytimer);
    $Q(".noty span").innerHTML = $text
    if (error == true) { $Q('.noty').classList.add("noty-error"); }
    $Q(".noty").style.cssText = "top:-200px; opacity:0; display:block";
    $TO(() => { $Q(".noty").style.cssText = ""; }, 10);
    window.notytimer = $TO(() => {
        $Q(".noty-btn-close").click();
    }, 4000);
}

function NotyCL() {
    $Q(".noty").style.cssText = "top:-200px; opacity:0; display:block";
    $TO(() => { $Q(".noty").style.cssText = " display:none"; }, 300);
}

//через cssText просто проще
function closePopup() {

    $Q('.popup').style.cssText = "display:flex; transform: scale(2); opacity:0;";
    $Q('.popup-back').style.cssText = "display:block; overflow:hidden; background-color: rgba(0,0,0,0);";
    setTimeout(() => {
        $Q('.popup').style.display = 'none';
        $Q('.popup-back').style.display = 'none';
    }, 500);


}



function showPopup() {
    $Q('.popup').style.cssText = "display:flex; transform: scale(0.5); opacity:0;";
    $Q('.popup-back').style.cssText = "display:block; background-color: rgba(0,0,0,0);";


    setTimeout(() => {
        $Q('.popup').style.cssText = "display:flex; transform: scale(1); opacity:1;";
        $Q('.popup-back').style.cssText = "display:block; background-color: rgba(0,0,0,0.5);";
    }, 10);
}

function submitForm(event) {
    event.preventDefault();
    let pre = ' <img src="img/pre.gif" class="pre" width="39" style="vertical-align:middle">';
    let cont = $Q('.gf-button').innerHTML;
    $Q('.gf-button').disabled = true;
    $Q('.gf-button').innerHTML = pre;
    let formdata = new FormData($Q('#gf-form'));
    $Ajax("feed.php", 'POST', formdata, (resp, xhr) => {
        if (xhr.status == 200) {
            json = JSON.parse(resp);
            if (json.success) {
                event.target.reset();
                Notyfy(json.success);
                closePopup();
            }
            if (json.error) {
                Notyfy(json.error, true);
            }
        }
        $Q('.gf-button').disabled = false;
        $Q('.gf-button').innerHTML = cont;
    });
}



function numberInput(key, e) {
    val = $Q("#gf-phone").value;
    e.preventDefault();

    e.key ? keyval = e.key.trim() : keyval = "";

    if (key == 8 && val.length > 0) {

        if (val[val.length - 1].match(/[\-\(\)]/g)) {

            val = val.substr(0, val.length - 1);
        }

        $Q("#gf-phone").value = val.substr(0, val.length - 1);
        restruct();
        return;
    }


    if (!keyval.match(/[\d]/g)) {

        return;
    }

    $Q("#gf-phone").value += keyval;
    restruct();
}



function restruct() {
    let val = $Q("#gf-phone").value.replace(/[\+\-\(\)]/g, '');
    val = val.replace('+8', '+7');
    if (val.length == 0) { val = '7'; }
    val = val.trim();
    let mat = $Q("#gf-phone").getAttribute("placeholder").replace('+7', '+0');


    let a = []
    let n = 0;

    for (let i = 0; i < mat.length; i++) {
        if (mat[i] !== '0') { a[i] = mat[i]; } else {
            if (n >= val.length) { break; }
            a[i] = val[n]; n++

        }
    }
    $Q("#gf-phone").value = a.join('');
}

function pasteNum(e) {
    e.preventDefault();
    val = e.clipboardData.getData('text/plain').replace(/[^\d]/, '');
    if (val.length >= 11 && val[0] == '7') { val = val.substr(1, 10); }
    $Q("#gf-phone").value += val;
    restruct();
}


window.addEventListener('load', function () {

    $Q(".popup-btn-close").addEventListener('click', closePopup);
    $Q(".btn-getfile").addEventListener('click', showPopup);
    $Q(".popup-gf-form").addEventListener('submit', submitForm);
    $Q("#gf-phone").addEventListener('keydown', function (e) { numberInput(e.keyCode || e.charCode, e); });
    $Q("#gf-phone").addEventListener('click', function (e) { restruct(); });
    $Q("#gf-phone").addEventListener('paste', pasteNum);

})





