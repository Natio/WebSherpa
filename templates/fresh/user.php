<script>
$(document).ready(function(e) {
    $("#changePassword").click(function(){
		$("#formChangePassword").toggle(250);
		$("#changePassword").hide(200);	
	});
        $("#changePasswordButton").click(function(e){
            e.preventDefault();
            changePassword();
        });
});

    function changePassword(){
        $("#resultPar").hide();
        var old = $("#oldPasswordInput").val();
        var newP = $("#newPasswordInput").val();
        var newConf = $("#confirmPasswordInput").val();
        
        if(newP.length < 3 || newP != newConf){
            $("#resultPar").text("Your password and confirmation password do not match").show(100);
            return;
        }
        
        var payload = {"oldPass" : old, "newPass" : newP};
        
        $(".ajaxIndicator").show(100);
        $("#changePasswordButton").hide();
        
        $.ajax({
            type: "POST",
            url: "/usersajax/changepwd",
            data: payload,
            dataType: "json",
            beforeSend: function(x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(msg){
                $(".ajaxIndicator").hide();
               $("#formDivContainer").hide();
               $("#resultPar").css("color","green").css("font-size","20px").text(msg.result).show();
            },
            error: function(response){
                $(".ajaxIndicator").hide();
                var json = JSON.parse(response.responseText);
                if(typeof json.error != "undefined"){
                    $("#resultPar").text(json.error).show();
                    
                    $("#changePasswordButton").show();
                    return;
                }
                
                $("#registerContainer").hide(250, function() {
                   
                    $("#resultPar").text("Error changing password :( please try later").show();
                });
                
            }
        });
    }

</script>
<div id="userContainer">
    <h3><? echo $this->result['username']; ?></h3>
    <a href="/sites/user?id=<? echo $this->result['user_id']; ?>">Show reviews</a>
    <p>Email: <? echo $this->result['email']; ?></p>
    <p>Name: <? echo $this->result['name']; ?></p>
    <p>Surname: <? echo $this->result['surname']; ?></p>
    <p>Member since: <? echo $this->result['member_since']; ?></p>
    <? if($this->getUser()->getAccountType() === PCModelUser::$TYPE_DEFAULT):?>
    <a href="#" id="changePassword">Change Password</a>
    <form id="formChangePassword" style="display:none">
        <div id="formDivContainer">
            <label for="oldPasswordInput">Your old password</label>
            <input type="password" placeholder="old password" id="oldPasswordInput" name="password"/>
            <label for="newPasswordInput">new password</label>
            <input type="password" placeholder="new password" id="newPasswordInput"/>
            <label for="confirmPasswordInput">confirm password</label>
            <input type="password" placeholder="confirm password" id="confirmPasswordInput"/>
            <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
            <button type="submit" class="changeButton" id="changePasswordButton">Change</button>
        </div>
        <p id="resultPar" style="display:none"></p>

    </form>
    <? endif;?>
</div>