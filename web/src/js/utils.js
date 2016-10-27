/**
 * Created by ASUS on 2016/10/25.
 */

function addUrlPara(name, value, url) {
    var currentUrl = url.split('#')[0];
    if (/\?/g.test(currentUrl)) {
        if (/name=[-\w]{4,25}/g.test(currentUrl)) {
            currentUrl = currentUrl.replace(/name=[-\w]{4,25}/g, name + "=" + value);
        } else {
            currentUrl += "&" + name + "=" + value;
        }
    } else {
        currentUrl += "?" + name + "=" + value;
    }
    if (url.split('#')[1]) {
        window.location.href = currentUrl + '#' + url.split('#')[1];
    } else {
        window.location.href = currentUrl;
    }
}