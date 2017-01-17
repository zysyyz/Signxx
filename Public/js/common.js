function create_code(){
    document.getElementById('valid').src = '/Home/login/getVerifyImg?' + Math.random() * 10000;
}