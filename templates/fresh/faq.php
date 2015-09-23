<script>
    $(document).ready(function(e) {
        var el = $(".faqTitleContainer");
        el.click(function(e) {

            var container = $(this).closest(".faqElement").children(".faqAnswerContainer");
            if (!container.is(":visible")) {
                container.delay(100).show(500);
                $(this).removeClass("bottomBorderRadius");
            }
            else {
                container.hide(500);
                $(this).addClass("bottomBorderRadius");
            }
        });
    });

</script>
<div class="faqElement">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: How can I download WebSherpa ?</p>
    </div>
    <div class="faqAnswerContainer">
        <p class="faqAnswer">You can download WebSherpa from Chrome Web Store</p>
    </div>
</div>
<div class="faqElement">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: How can I submit a new review ?</p>
    </div>
    <div class="faqAnswerContainer">
        <p class="faqAnswer"> To submit a review, first go on the website you want review, then click the WebSherpa icon on the Chrome toolbar. A pop up will open on which you will be able to post your review.</p>
    </div>
</div>
<div class="faqElement ">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: What does it mean the exclamation mark on the WebSherpa icon ?</p>
    </div>
    <div class="faqAnswerContainer ">
        <p class="faqAnswer"> If you see an exclamation mark it means you are not logged in. To log in, you must click <u>Login</u> in the upper right side of this page. Should the problem persist,
            uninstall and reinstall the plugin (see <a href="/page/instructions">Instructions</a>).
 </p>
    </div>
</div>
<div class="faqElement">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: How are votes calculated ?</p>
    </div>
    <div class="faqAnswerContainer ">
        <p class="faqAnswer"> The visualized vote is the arithmetic average (rounded to the closer whole number ie: 6.7=7) of the votes given by each voter for a specific website.</p>
    </div>
</div>
<div class="faqElement">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: Is it available a WebSherpa version for Firefox, Safari or Opera ?</p>
    </div>
    <div class="faqAnswerContainer">
        <p class="faqAnswer"> Not yet, but we are working on it. </p>
    </div>
</div>
<div class="faqElement">
    <div class="faqTitleContainer bottomBorderRadius">
        <p class="faqTitle">Q: How can I delete my account ?</p>
    </div>
    <div class="faqAnswerContainer">
        <p class="faqAnswer"> To delete an account you need to write us by the <a href="/page/about">Contact us</a> module </p>
    </div>
</div>

