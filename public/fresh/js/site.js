$(document).ready(function() {
    
    
    
    $("#addReviewOpener").click(function(e) {
        e.preventDefault();
        $(this).hide();
        $("#reviewAddForm").slideDown(300);
        
        return false;
    });
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


    $("#addReviewSendButton").click(function(e) {
        e.preventDefault();
        addReview();
        return false;
    });

    function addReview() {
        var rel = $('#reliabilitySlider').val();
        var cont = $('#contentsSlider').val();
        var usa = $('#usabilitySlider').val();
        var comment = $('#comment').val();
        var sendButton = $('#addReviewSendButton');
        var resultP = $('#reviewResult');
        resultP.show();
        sendButton.hide(200);
        $(".ajaxIndicator").show(100);

        var payload = {
            "site_identifier": SITE_ID,
            "language_code": "EN",
            "comment": comment,
            "contents": cont,
            "usability": usa,
            "reliability": rel
        };

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


                resultP.text("Review Added!!!!");
                $(".ajaxIndicator").hide(100);

                setTimeout(function() {
                    location.reload();

                }, 2000);

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



    var canvasVote = document.getElementById('voteCanvas');
    drawVote(averageVote.toFixed(1), 130, 175, canvasVote, getColorFromVote(averageVote));

    var canvasRel = document.getElementById('reliabilityCanvas');
    drawVote(averageReliability.toFixed(1), 100, 175, canvasRel, getColorFromVote(averageReliability));

    var canvasUsa = document.getElementById('usabilityCanvas');
    drawVote(averageUsability.toFixed(1), 100, 175, canvasUsa, getColorFromVote(averageUsability));

    var canvasCont = document.getElementById('contentsCanvas');
    drawVote(averageContents.toFixed(1), 100, 175, canvasCont, getColorFromVote(averageContents));

    function drawVote(vote, h, w, canvas, color) {
        if (Math.abs(parseFloat(vote) - 10.0) < 0.001) {
            vote = "10";
        }

        var ctx = canvas.getContext('2d');

        var canvasH = canvas.height;
        var canvasW = canvas.width;

        //var grd = ctx.createLinearGradient(0, 0, h, w);
        //grd.addColorStop(0.1, shadeColor(color, -30));
        //grd.addColorStop(0.9, color);

        ctx.fillStyle = color;//grd;//"blue";
        ctx.font = (h) + "px Calibri";

        var metrics = ctx.measureText(vote);
        var halfH = metrics.height / 2;
        var height = canvasH - 10;
        var width = (w / 2) - (metrics.width / 2);

        ctx.fillText(vote, width, height);

        /*var strokeGrd = ctx.createLinearGradient(h, w, 0, 0);
        strokeGrd.addColorStop(0.1, '#000');
        strokeGrd.addColorStop(0.8, '#777');
        */
        ctx.lineWidth = 1;
        ctx.strokeStyle = shadeColor(color,-50);//"#aaa";
        ctx.strokeText(vote, width, height);

    }
    


    function sendReport(button) {
        var txt = $("#testoSegnalazione").val();
        var l = txt.length;
        if (txt.length > 200 || txt.length < 10) {
            $("#resultSegnalazione").text("Please enter at least 10 characters");
            return;
        }

        $("#sendSegnalazioneButton").hide();
        $(".ajaxIndicator").show();


        var payload = {"reviewId": identifierSegnalazioneReview, "text": txt};

        $.ajax({
            url: "/sitesajax/reportspam",
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

                    $("#resultSegnalazione").text(msg.error);
                    $(".ajaxIndicator").hide(100);
                    $("#sendSegnalazioneButton").show();
                    return;

                }


                $("#resultSegnalazione").text("Report sent!!");
                $(".ajaxIndicator").hide(100);

                setTimeout(function() {
                    $("#sendSegnalazioneButton").show();
                    $("#segnalazionePopup").hide(200);

                });

            },
            error: function() {
                $(".ajaxIndicator").hide(100);

                $("#resultSegnalazione").text("Something went wrong");
                $("#sendSegnalazioneButton").show();

            }
        });

    }

    $(".reviewContainer").mouseenter(function() {
        $(".reportSpam", this).show();
    });


    $(".reviewContainer").mouseleave(function() {
        $(".reportSpam", this).hide();
    });

    $(".reportSpam").click(function() {
        var reviewId = $(this).attr('data-revid');
        identifierSegnalazioneReview = reviewId;
        $("#segnalazionePopup").show(200);
    });

    $("#sendSegnalazioneButton").click(function(event) {
        event.preventDefault();
        sendReport(this);
        return false;
    });


});