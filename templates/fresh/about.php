<style>
    
    h1.flexibleTitle{ text-align: center;}
    p.contactTitle{
        width:700px;
        margin-top:20px;
        margin-left:auto;
        margin-right:auto;
        text-align: center;
        font-weight:bold;
    }

    #contactForm{
        margin-left:auto;
        margin-right:auto;
        width:400px;
        margin-top:40px;
        font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-weight:200;
        margin-left: auto;
        margin-left: auto;
    }

    #contactForm input[type="text"]{
        display:block;
        width:200px;
        

    }
    #contactForm label{
        display:block;
        margin-top:10px;
        margin-bottom:10px;
    }
    #contactForm textarea{
        width:200px;
        height:200px;
    }
    #contactForm #sendMail  {
        margin-left: 0px;
        left: 50%;
        margin-right: auto;
        margin-left:auto;
        margin-top:10px;
        margin-bottom:10px;

        display: block;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #fff;
        padding: 4px 20px;
        background: -moz-linear-gradient(  top,  #96a6c5 0%,  #003366);
        background: -webkit-gradient(  linear, left top, left bottom,  from(#96a6c5),  to(#003366));
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        border: 1px solid #003366;
        -moz-box-shadow: 0px 1px 3px rgba(000,000,000,0.5),  inset 0px 0px 1px rgba(255,255,255,0.5);
        -webkit-box-shadow: 0px 1px 3px rgba(000,000,000,0.5),  inset 0px 0px 1px rgba(255,255,255,0.5);
        background: -ms-linear-gradient(top,
		#96a6c5 0%,
		#003366);
        box-shadow: 0px 1px 3px rgba(000,000,000,0.5),  inset 0px 0px 1px rgba(255,255,255,0.5);
        text-shadow: 0px -1px 0px rgba(000,000,000,0.7),  0px 1px 0px rgba(255,255,255,0.3);
        background: -o-linear-gradient(top, #96a6c5 0%, #003366);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#96a6c5', endColorstr='#003366');
    }
    #contactForm .captchaContainer{
        margin-top: 10px;
    }

    .captchaContainer{
        text-align: center;
       
        margin-left: auto;
        margin-right: auto;
    }
    #mailResultContainer{
        width:inherit;
        text-align: center;
    }
    
    .mainContainer .flexibleTitle{
        margin-left:100px;
        width:inherit;
    }
</style>

<script>
    $(document).ready(function(e) {

        $("#sendMail").click(function(e) {
            e.preventDefault();
            handleSendMail();
            return false;
        });


        function handleSendMail() {
            var $inputs = $('#contactForm :input');
            var name = $inputs[0];
            var mail = $inputs[1];
            var object = $inputs[2];
            var textArea = $inputs[3];
            var challenge = $inputs[4];
            var response = $inputs[5];

            if (!validateEmail($(mail).val())) {
                $("#mailResultContainer").text("email is not valid!!");
                return;
            }
            if ($(name).val().length <= 3) {
                $("#mailResultContainer").text("name is not valid!!");
                return;
            }
            if ($(object).val().length <= 3) {
                $("#mailResultContainer").text("object is not valid!!");
                return;
            }
            if ($(textArea).val().length <= 3) {
                $("#mailResultContainer").text("object is not valid!!");
                return;
            }

            $(".ajaxIndicator").show(100);
            $("#sendMail").hide();

            var payload = {
                "name": $(name).val(),
                "email": $(mail).val(),
                "object": $(object).val(),
                "text": $(textArea).val(),
                "recaptcha_response_field": $(response).val(),
                "recaptcha_challenge_field": $(challenge).val()
            };
           
             $.ajax({
                type: "POST",
                url: "/usersajax/contact",
                data: payload,
                dataType: "json",
                beforeSend: function(x) {
                    if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/json;charset=UTF-8");
                }
                },
                success: function(msg) {
                    $(".ajaxIndicator").hide(100);
                    $("#sendMail").show();
                    if (typeof msg.error != "undefined") {
             
                        $("#mailResultContainer").text(msg.error);
                        Recaptcha.reload();
                        return;
                     }
             
             
                    $("#contactForm").hide(100, function() {
                    //visualizzare 
                    $("#mailResultContainer").text("Message Sent!!!");
                    });
             
             
                 },
                error: function(response) {
                    $(".ajaxIndicator").hide(100);
                    $("#sendMail").show();
                    var json = JSON.parse(response.responseText);
                    if (typeof json.error != "undefined") {
                        $("#mailResultContainer").text(msg.captcha_error);
             
                         Recaptcha.reload();
                        return;
                    }
                    $("#mailResultContainer").text("Error sending message :( please try later");
             
             
                }
             });

        }

    });
</script>

<style>
    p.aboutP{ text-align: center;}
</style>

<h1 class="flexibleTitle">About</h1>
<article>
    <p class="aboutP">
        WebSherpa is a tiny Italian startup with great ambitions!!!
    </p>
</article>

<p class="contactTitle">Have you got some questions ? Write us, we have answers :)</p>
<form id="contactForm">
    <label for="user_name">Name</label>
    <input type="text" name="user_name" placeholder="name..." />
    <label for="user_email">Email</label>
    <input type="text" name="user_email" placeholder="email..." />
    <label>Object</label>
    <input type="text" name="mail_object" placeholder="object..."/>
    <label>Text (max 250 char)</label>
    <textarea id="mailTextArea"  maxlength='250'></textarea>
    <div class="captchaContainer">
        <? echo $this->getCaptcha(); ?>
    </div>
    <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
    <button id="sendMail">Send</button>
</form>
<div id="mailResultContainer">

</div>