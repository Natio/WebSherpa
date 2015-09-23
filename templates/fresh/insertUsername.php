<style>
    form#addUsernameForm{
        width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    form#addUsernameForm h3{
        text-align: center;
        font-size: 17px;
    }
    
    form#addUsernameForm input{
        width: 300px;
        height: 30px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 10px;
        text-align: center;
        font-weight: bold;
    }
    form#addUsernameForm .errorPar{
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        max-width: 300px;
        color: rgb(250,60,100);
    }
    
    form#addUsernameForm button[type="submit"]{
        display: block;
        width: 100px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 10px;
    }
    
</style>
<? if(isset($this->result['show_email'])):?>
<form action="/social/twittercallback" method="GET" id="addUsernameForm" class="niceContainer" >
<? else: ?>
<form action="/social/facebookcallback" method="GET" id="addUsernameForm" class="niceContainer" >
<? endif;?>
    <h3>Choose a username for your account</h3>
    <input type="text" name="reg_username" id="regUsernameInput" placeholder="Username..."/>
    <? if(isset($this->result['show_email'])):?>
    <input type="text" name="reg_email" id="regUsernameEmail" placeholder="email..."/>
    <? endif; ?>
    <p class="errorPar"><? if(isset($this->result['text_error'])) echo $this->result['text_error']; ?></p>
    <button type="submit" class="greenButton">Send</button>
</form>
