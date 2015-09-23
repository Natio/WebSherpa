$(document).ready(function(e) {
    
    var can_show_scroll_to_top = $(window).width() > 1200;
    
    function showHideScrollItem() {
        if ($(this).scrollTop() > 30) {
            $('#scrollToTopItem').fadeIn();
        } else {
            $('#scrollToTopItem').fadeOut();
        }
    }
    
    $(window).resize(function(){
        can_show_scroll_to_top = $(window).width() > 1200;
        if ( can_show_scroll_to_top ) {
            $('#scrollToTopItem').fadeIn();
        } else {
            $('#scrollToTopItem').fadeOut();
        }
    });

    $(window).scroll(function() {
        if (can_show_scroll_to_top) {
            showHideScrollItem();
        }
    });
    
    
    

    $('#scrollToTopItem').click(function() {
        $('body,html').animate({scrollTop: 0}, 400);
    });

    $("#searchImage").click(function(e){
        $(this).hide('fast',function(){
            $('#searchContainer').toggle('slow',function(){
                $('#searchField').focus();
            });
        });
        return false;
    });
    
    $("#loginButton").click(function(e) {
        e.preventDefault();
        handleLogin();
        return false;
    });

    $("#registerButton").click(function(e) {
        e.preventDefault();
        handleRegister();
        return false;
    });

    $("#sendLostPasswordButton").click(function(e) {
        e.preventDefault();
        handlerPasswordLost();
        return false;
    });
    $("#termsLabel").click(function(e){
        window.open("/page/tos");
    });
    
    
    function handlerPasswordLost() {
        
        var email = $("#lostPasswordMailField").val();
        if (validateEmail(email) == false) {
            $("#lostPasswordLabel").text("Please insert a valid email");
            return;
        }
        
        $(".ajaxIndicator").show(100);
        $("#sendLostPasswordButton").hide();

        $.ajax({
            type: "GET",
            url: "/usersajax/repass?email=" + encodeURIComponent(email),
            dataType : "json",
            success: function(msg) {
                
                if (typeof msg.error != "undefined") {
                    
                    $("#lostPasswordLabel").text(msg.error);
                    $(".ajaxIndicator").hide(100);
                    $("#sendLostPasswordButton").show();
                    return;
                    
                }
                
                $("#sendLostPasswordButton").hide("slow");
                $("#lostPasswordMailField").hide("slow");
                $("#lostPasswordLabel").text("Now please check your inbox (also in the spam folder)");
                $(".ajaxIndicator").hide(100);
                $("#sendLostPasswordButton").show();
                
            },
            error: function() {
                $(".ajaxIndicator").hide(100);
                $("#sendLostPasswordButton").hide("slow");
                $("#lostPasswordMailField").hide("slow");
                $("#lostPasswordLabel").text("Something went wrong");
                $(".ajaxIndicator").hide(100);
                $("#sendLostPasswordButton").show();

            }
        });
    }

    function handleLogin() {
        removeError("loginErrorLabel");
        var usernameInput = $("#usernameLogin");
        var pwdInput = $("#passwordLogin");

        

        if (usernameInput.val().length == 0) {
            showError("Invalid username", "loginErrorLabel");
            return;
        }

        if (pwdInput.val().length == 0) {
            showError("Invalid password", "loginErrorLabel");
            return;
        }

        var shaObj = new jsSHA(pwdInput.val(), "ASCII");
        var pwdHash = shaObj.getHash("SHA-512", "HEX");

        $(".ajaxIndicator").show(100);
        $("#loginButton").hide();

        $.ajax({
            type: "GET",
            url: "/usersajax/login?uname=" + encodeURIComponent(usernameInput.val()) + "&pwd=" + encodeURIComponent(pwdHash),
            success: function(msg) {

                $(".ajaxIndicator").hide(100);
                $("#loginButton").show();
                
                if(window.location.pathname == "/page/register"){
                    window.location.href = "/";
                }
                else{
                    window.location.reload();
                }
                
                

            },
            error: function() {

                showError("Wrong username or password :(", "loginErrorLabel");
                $(".ajaxIndicator").hide(100);
                $("#loginButton").show();
            }
        });

    }

    function handleRegister() {
        $("#registerResultContainer").hide();
        $("#registerForm").show();
        removeError("registerErrorLabel");
        
        var name = $("#nameReg");
        var surname = $("#surnameReg");
        var email = $("#emailReg");
        var password = $("#passReg");
        var rePass = $("#passConfReg");
        var username = $("#usernameReg");
        var agree = $("#termsCheckbox");

        var checked = agree.is(':checked');


        if (name.val().length == 0) {
            showError("Insert a name", "registerErrorLabel");
            return;
        }
        if (surname.val().length == 0) {
            showError("Insert a surname", "registerErrorLabel");
            return;
        }
        if (username.val().length < 5 || validateUsername(username.val()) == false) {
            showError("Insert a valid Username; min 6 characters use only characters and numbers and \"_\"", "registerErrorLabel");
            return;
        }
        if (validateEmail(email.val()) == false) {
            showError("Insert a valid email", "registerErrorLabel");
            return;
        }
        if (password.val().length < 6) {
            showError("Password too short, min 6 characters!", "registerErrorLabel");
            return;
        }

        if (rePass.val() != password.val()) {
            showError("Password and confirmation doesen't match", "registerErrorLabel");
            return;
        }
        if (checked == false) {
            showError("You have to agree", "registerErrorLabel");
            return;
        }

        $(".ajaxIndicator").show(100);
        $("#registerButton").hide();

        var $inputs = $('#registerForm :input');

        var payload = {
            "username": username.val(),
            "name": name.val(),
            "surname": surname.val(),
            "email": email.val(),
            "password": password.val(),
            "recaptcha_response_field": $($inputs[7]).val(),
            "recaptcha_challenge_field": $($inputs[6]).val()
        };

        $.ajax({
            type: "POST",
            url: "/usersajax/register",
            data: payload,
            dataType: "json",
            beforeSend: function(x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(msg) {
                $(".ajaxIndicator").hide(100);
                $("#registerButton").show();
                if (typeof msg.captcha_error != "undefined") {
                    showError(msg.captcha_error, "registerErrorLabel");
                    if (typeof _gaq !== 'undefined') {  _gaq.push(['_trackEvent', 'Captcha Error', msg.captcha_error]); }
                    Recaptcha.reload();
                    return;
                }

                if (typeof _gaq !== 'undefined') { _gaq.push(['_trackEvent', 'Utente Registrato', "OK"]);}
                $("#registerForm").hide(100, function() {
                    /*
                     var resultContainer = $(".resultContainer.resultOk")
                     $(".resultContainer.resultOk p").text("User added, now please login :)");
                     resultContainer.slideDown(200);
                     */
                    $("#registerResultContainer").show();
                });


            },
            error: function(response) {
                $(".ajaxIndicator").hide(100);
                $("#registerButton").show();
                var json = JSON.parse(response.responseText);
                if (typeof json.error != "undefined") {
                    showError(json.error, "registerErrorLabel");
                    if (typeof _gaq !== 'undefined') {_gaq.push(['_trackEvent', 'Register Error', json.error]);}
                    Recaptcha.reload();
                    return;
                }
                if (typeof _gaq !== 'undefined') {_gaq.push(['_trackEvent', 'Register Error', "Errore Sconosciuto"]);}
                showError("Error adding User :( please try later", "registerErrorLabel");


            }
        });
    }

    

    
    function showError(error, itemId) {
        $("#" + itemId).text(error).show();
    }

    function removeError(itemId) {
        $("#" + itemId).hide();
    }

});

/**
     * Valida la mail
     * @return boolean
     */

    function validateEmail(email) {

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address = email;
        if (reg.test(address) == false) {


            return false;
        }
        return true;
    }
    function validateUsername(user) {
        var reg = /^[a-zA-Z0-9_\-\.]{3,15}$/;
        return reg.test(user);
    }
