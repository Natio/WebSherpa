<script>
    $(document).ready(function(e) {
        $(".titleContainer").show("slow",function(){
            $(".welcomeSubtitle").fadeIn("slow",function(){
                $("#welcomeContainer").addClass("arrowBackground",5000);
                $("#chromeBarContainer").fadeIn("slow");
            });
        });
    });
</script>
<div id="welcomeContainer">
    <div class="titleContainer" style="display:none;">
        <h1 class="welcomeTitle">Welcome</h1>
    </div>

    <p class="welcomeSubtitle" style="display:none;">Now please register or login in order to use your plugin. Follow the green arrow</p>
    <p class="welcomeSubtitle" style="display:none;">...after...</p>
    <p class="welcomeSubtitle" style="display:none;">You can use the plugin by clicking the icon</p>
    <div id="chromeBarContainer" style="display:none;"></div>
</div>
