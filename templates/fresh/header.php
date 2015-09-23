<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="google-site-verification" content="E996dK_vMSgpvCZWWjmBHp7SzEqqHCtu3sZ7zphkukY" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <? $this->linkStyleSheets(); ?>
        <? $this->linkStyleScripts(); ?>
        <link rel="icon" href="/public/fav.ico" />
        <title><? echo $this->getPageTitle(); ?></title>
    </head>

    <body>
        <? $this->addAnalytics(); ?>
        <script>
            $(document).ready(function(e) {

                $(".popupOverlay .lostPassword").click(function(e) {
                    e.preventDefault();
                    $(".popupOverlay").hide("slow", function(e) {
                        $("#passwordLostPopup").show("slow");
                    });
                    return false;
                });

                $("#loginLink").click(function(e) {
                    e.preventDefault();
                    $("#loginPopup").fadeIn("fast");
                });

                $(".closePopup").click(function(e) {
                    e.preventDefault();
                    $(".popupOverlay").fadeOut("fast");
                });

            });
        </script>
        <? $user = $this->getUser(); ?>
        <div id="pageContainer">
            <div id="scrollToTopItem">Top</div>
            <!-- Navigation -->
            <header>
                <div id="navigationBarContainer">
                    <div id="navigationBar"> <a href="/"> <img src="<? echo $this->getImageDirectoryPath() . "logo_mini.png" ?>" class="logoMini" height="30" width="20" alt="websherpa logo"> </a>
                        <nav>
                            <div id="pageNavigation">
                                <div class="navigationItem"><a href="/">Home</a></div>
                                <div class="navigationItem"><a href="/page/downloads">Download</a></div>
                                <div class="navigationItem"><a href="/page/about">About</a></div>
                                <div class="navigationItem"><a href="/page/faq">F.A.Q.</a></div>
                                <? if(isset($user)):?>
                                <div class="navigationItem"><a href="/sites/add">Add Website</a></div>
                                <? endif; ?>
                            </div>
                        </nav>
                        <div id="loggedContainer">
                            <?
                            if (isset($user) == FALSE) {
                                echo "<a href=\"#\" id=\"loginLink\">Login</a> or <a href=\"/page/register\" id=\"registerLink\">Register</a>";
                            } else {
                                echo "<a href=\"/user/profile?id=";
                                echo $user->getIdentifier();
                                echo "\">" . $user->getUsername() . "</a>, <a href=\"/page/logout\">Logout</a>";
                            }
                            ?>

                        </div>
                        <img id="searchImage" src="<? echo $this->getImageDirectoryPath() . "search_icon.png" ?>" height="20" width="20" alt="Search icon"/>
                        
                        <div id="searchContainer">
                            <form method="get" action="/sites/search" >
                                <input type="text" id="searchField" placeholder="Search..." name="host_name"/>
                            </form>
                        </div>
                        
                    </div>
                </div>
                <div class="popupOverlay" id="passwordLostPopup">
                    <div class="popupBox" style="margin-left:-200px;margin-top:-100px;width:400px;height:200px;">
                        <a class="closePopup" href="#">close</a>
                        <div class="inputContainer" id="resetPassInputContainer" style="text-align:center; ">

                            <input type="text" name="email" id="lostPasswordMailField" placeholder="You email:" />
                            <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
                            <label id="lostPasswordLabel">Insert your email. You will recive a mail with the instruction to reset your password</label>

                            <button type="submit" id="sendLostPasswordButton" class="blueButton">Send</button>

                        </div>
                    </div>
                </div>
                <div class="popupOverlay" id="loginPopup">
                    <? $showSocial = PCConfigManager::sharedManager()->getBoolValue('SOCIAL_LOGIN');
                            
                            ?>
                    <div class="popupBox" style="margin-left:-200px;margin-top:-100px;width:400px; <?echo $showSocial ? "height:380px;" : "height:250px;";?>"> <a class="closePopup" href="#">close</a><a class="lostPassword" href="#">Password Lost?</a>
                        <? if ($showSocial): ?>
                        <div class="inputContainer socialLoginContainer">
                            <a href="/social/tw_login"><div class="socialSprite twitterBackground"></div></a>
                            <a href="/social/fb_login"><div class="socialSprite facebookBackground"></div></a>
                            <h3>Or</h3>
                        </div>
                        <? endif; ?>
                        <div class="inputContainer" <? if(!$showSocial) echo 'style="margin-top:40px;"'; ?>>
                            <input type="text" name="username" id="usernameLogin" placeholder="Username..." />
                            <input name="password" type="password" id="passwordLogin" placeholder="Password" />
                            <p id="loginErrorLabel" style="display:none;">dfqfqwefqrwfqerferfw erg erg</p>
                            <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
                            <button id="loginButton" class="greenButton">Login</button>
                        </div>
                    </div>
                </div>
            </header>
            <div id="bodyContainer">