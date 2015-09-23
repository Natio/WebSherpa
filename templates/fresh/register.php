<? if (PCConfigManager::sharedManager()->getBoolValue('SOCIAL_LOGIN')): ?>
    <div class="socialRegisterContainer niceContainer">
        <h3>Register using Facebook or Twitter</h3>
        <div class="socialButtons">
            <a href="/social/tw_login"><div class="socialSprite twitterBackground" style="float:left; margin-left: 20px;"></div></a>
            <a href="/social/fb_login"><div class="socialSprite facebookBackground" style="float:right; margin-right: 20px;"></div></a>
            <div style="clear:both;"></div>
        </div>
        <h3>Or</h3>
    </div>
<? endif; ?>
<div id="siteRegisterContainer">
    <div class="inputContainer" id="registerInputContainer">
        <form id="registerForm">
            <input type="text" placeholder="Name..." id="nameReg"/>
            <input type="text" placeholder="Surname..." id="surnameReg" />
            <input type="text" placeholder="Username..." id="usernameReg"/>
            <input type="text" placeholder="Email..." id="emailReg"/>
            <input type="password" placeholder="Password..." id="passReg" />
            <input type="password" placeholder="Password conf" id="passConfReg" />

            <div class="captchaContainer">
                <? echo $this->getCaptcha(); ?>
            </div>
            <input type="checkbox" id="termsCheckbox"/>
            <label id="termsLabel">I agree to te the terms of service</label>
            <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
            <p id="registerErrorLabel" style="display:none;">Lorem ipsum dolor sit amet</p>
            <button type="submit" id="registerButton" class="blueButton">Register</button>
        </form>
    </div>
    <div id="registerResultContainer" style="display:none;">
        <p id="registerResultLabel">Registered :) Now please login using your username and password</p>
    </div>
</div>