var rev_offset = 1;
$(document).ready(function(){

    $("#moreReviews").click(function(){
        
        $(".ajaxIndicator").show("fast");
       
        
        var url = "/sitesajax/review";
        $("#moreReviews").attr("disabled", "disabled");
        
        var data = null;
        if(SITE_ID > 0){
            data = "site_id="+SITE_ID+"&offset="+rev_offset;
        }
        else{
            data = "user_id="+USER_ID+"&offset="+rev_offset;
        }
        
        $.ajax({
            type: "GET",
            url: url,
            data : data,
            dataType: "json",
            success: function(msg){
               reviewsDownloaded(msg);
               $(".ajaxIndicator").hide("fast");
               
            },
            error: function(){
                $(".ajaxIndicator").hide("fast");
            }
        });
        
    });
    
    
    function reviewsDownloaded(reviews){
        if(reviews.length == 0) return;
        
        
        for(var i = 0; i < reviews.length ; i++){
            var review = reviews[i];
            var element = buildElementsForReview(review);
            $("#reviewsBox").append(element);
        }
        $("#moreReviews").removeAttr("disabled");
        rev_offset++;
    }
   
    function createVoteItem(vote, label){
        
        var color = getColorFromVote(vote);
        
        
        var r_voteItem = document.createElement('div');
        r_voteItem.className = "r_voteItem";
        
        var r_voteValue = document.createElement('div');
        r_voteValue.className = "r_voteValue reviewVote";
        r_voteValue.textContent = vote+"";
        r_voteValue.style.color = color;
        
        r_voteItem.appendChild(r_voteValue);
        
        var r_voteLabel = document.createElement('div');
        r_voteLabel.className = "r_voteLabel";
        r_voteLabel.textContent = label;
        
        r_voteItem.appendChild(r_voteLabel);
        
        return r_voteItem;
    }
    
    function buildElementsForReview(review){
        var article = document.createElement("article");
        var container = document.createElement("div");
        container.className = "reviewContainer";
        
        
        var r_titleBox = document.createElement('div');
        r_titleBox.className = "r_titleBox";
        
        
        var r_mainVoteContainer = document.createElement('div');
        r_mainVoteContainer.className = "r_mainVoteContainer";
        
        
        var color = getColorFromVote(review.vote);
        
        var mainVote = document.createElement('div');
        mainVote.className = "reviewVote mainVote";
        mainVote.textContent = review.vote;
        mainVote.style.color = color;
        
        r_mainVoteContainer.appendChild(mainVote);
        
        r_titleBox.appendChild(r_mainVoteContainer);
        
        
        
        var titleContainer = document.createElement('div');
        titleContainer.className = "titleContainer";
        
        var link = document.createElement('a');
        
        if (USER_ID < 0) {
           link.setAttribute("href", "/sites/user?id=" + review.userId);
            link.textContent = review.user;
        }
        else {
            link.setAttribute("href", "/sites/site?id=" + review.siteId);
            link.textContent = review.site;
        }
        
        titleContainer.appendChild(link);
        
        r_titleBox.appendChild(titleContainer);
        
        var reviewDate = document.createElement('div');
        reviewDate.className = 'reviewDate';
        reviewDate.textContent = review.date_added;
        r_titleBox.appendChild(reviewDate);
        
        container.appendChild(r_titleBox);
        //fine title box
        
        var votesContainer = document.createElement('div');
        votesContainer.className = "votesContainer";
        votesContainer.appendChild(createVoteItem(review.reliability, "Reliability"));
        votesContainer.appendChild(createVoteItem(review.usability, "Usability"));
        votesContainer.appendChild(createVoteItem(review.contents, "Contents"));
        container.appendChild(votesContainer);
        //fine voti
        
        var reviewTextConteiner = document.createElement('div');
        reviewTextConteiner.className = "reviewTextConteiner";
        var text = document.createElement('p');
        reviewTextConteiner.appendChild(text);
        text.textContent = review.comment;
        container.appendChild(reviewTextConteiner);
        article.appendChild(container);
        
        return article;
    }
});



