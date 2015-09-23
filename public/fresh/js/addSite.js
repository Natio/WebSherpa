$(document).ready(function() {
    $("#reliabilitySlider").change(function() {
        var voteText = parseFloat($(this).val()).toFixed(1);
        $('#reliabilityVal').text(voteText).css("color", getColorFromVote($(this).val()));
    });
    //aggiungo il listenrt per lo slider del voto
    $("#usabilitySlider").change(function() {
        var voteText = parseFloat($(this).val()).toFixed(1);
        $('#usabilityVal').text(voteText).css("color", getColorFromVote($(this).val()));
    });
    //aggiungo il listenrt per lo slider del voto
    $("#contentsSlider").change(function() {
        var voteText = parseFloat($(this).val()).toFixed(1);
        $('#contentsVal').text(voteText).css("color", getColorFromVote($(this).val()));
    });

    //aggiungo il listener per la textarea
    $("#comment").keyup(function(e) {
        var numberOfChar = this.value.length;
        $("#counterLabel").text("(" + (200 - numberOfChar) + " chars)");
    });

    $("#sendButton").click(function() {
        addSite();
    });

    downloadCategories();
});

function downloadCategories() {
    $.ajax({
        url: "/categories/all",
        dataType: "json",
        type: "GET",
        success: function(msg) {
            var vote = $("#categorySelect");
            for (var i = 0; i < msg.length; i++) {
                var obj = msg[i];
                var name = obj.name;
                var identifier = obj.identifier;
                var option = document.createElement("option");
                option.text = name;
                option.value = identifier;
                vote.append(option);
            }
        },
        error: function(xhr) {
            var k;
        }
    });
}

function addSite() {

    var selectCategory = $('#category');
    var category = null;
    if (selectCategory) {
        category = selectCategory.val();
    }


    var rel = $('#reliabilitySlider').val();
    var cont = $('#contentsSlider').val();
    var usa = $('#usabilitySlider').val();
    var language_code = "en";
    var comment = $('#comment').val();
    var site = $('#campoUrl').val();
    var resultP = $('#reviewResult');

    if (site.indexOf("http://") !== 0 && site.indexOf("https://") !== 0) {
        site = "http://" + site;
    }
    if (!isUrl(site)) {
        resultP.show();
        resultP.text("Please insert a valid URL");
        return;
    }

    var payload = {
        "siteUrl": site,
        "language_code": language_code,
        "comment": comment,
        "contents": cont,
        "usability": usa,
        "reliability": rel,
        "category": category
    };
    var sendButton = $("#sendButton");
    sendButton.hide();
    $(".ajaxIndicator").show(100);

    $.ajax({
        url: "/sitesajax/add",
        dataType: "json",
        type: "POST",
        data: payload,
        beforeSend: function(x) {
            if (x && x.overrideMimeType) {
                x.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(msg) {

            if (typeof msg.error != "undefined") {

                resultP.text(msg.error);
                $(".ajaxIndicator").hide(100);
                sendButton.show();
                return;

            }
            var a = document.createElement('a');
            a.href = site;
            document.location.href = "/sites/site/" + a.hostname;

        },
        error: function(xhr) {
            var jsonResult = JSON.parse(xhr.responseText);

            if (typeof jsonResult.error === "undefined") {
                resultP.text("Error adding review, please try later!!");
            }
            else {
                resultP.text(jsonResult.error);
            }
            $(".ajaxIndicator").hide(100);
            sendButton.show();
        }
    });

}

function isUrl(s) {
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(s);
}