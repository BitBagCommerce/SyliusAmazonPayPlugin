function getURLParameter(name, source) {
    return decodeURIComponent((new RegExp('[?|&|#]' + name + '=' +
        '([^&]+?)(&|#|;|$)').exec(source) || [,""])[1].replace(/\+/g,
        '%20')) || null;
}

var accessToken = getURLParameter("access_token", location.hash);

if (typeof accessToken === 'string' && accessToken.match(/^Atza/)) {
    document.cookie = "amazon_Login_accessToken=" + accessToken;
}

window.onAmazonLoginReady = function () {
    amazon.Login.setClientId('amzn1.application-oa2-client.bce33695af0245ceb924af2ede4b9877');
    amazon.Login.setUseCookie(true);
};
