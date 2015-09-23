<script type="text/javascript">

    var identifierSegnalazioneReview = -1;

    var averageVote = <? echo $this->result['averageVote']; ?>;
    var averageReliability = <? echo $this->result['reliability']; ?>;
    var averageUsability = <? echo $this->result['usability']; ?>;
    var averageContents = <? echo $this->result['contents']; ?>;

</script>

<script type="text/javascript">
<? if (isset($this->result['siteHost'])) : ?>
        var SITE_ID = <? echo $this->result['site_id']; ?>;
        var USER_ID = -1;
<? else : ?>
        var USER_ID = <? echo $this->result['user_id']; ?>;
        var SITE_ID = -1;
<? endif; ?>

</script>

<div id="websiteBox" class="niceContainer">
    <?
    $isSite = isset($this->result['siteHost']);
    if ($isSite) : ?>
        <div class="websiteContainer">
            <div class="websiteName"><a href="http://<? echo $this->result['siteHost']; ?>"><? echo $this->result['siteHost']; ?></a></div>
            <table class="votesTable" cellspacing="0"  border="0">
                <tr class="vote">
                    <td><canvas id="voteCanvas" width="175" height="120"></canvas></td>
                    <td><canvas id="reliabilityCanvas" width="175" height="120"></canvas></td>
                    <td><canvas id="usabilityCanvas" width="175" height="120"></canvas></td>
                    <td><canvas id="contentsCanvas" width="175" height="120"></canvas></td>
                </tr>
                <tr class="description">
                    <td class="voteName">Average vote</td>
                    <td class="voteName">Reliability</td>
                    <td class="voteName">Usability</td>
                    <td class="voteName">Contents</td>
                </tr>
            </table>
            <div class="descriptionItem">
                <div class="title">Number of votes</div>
                <div class="value"><? echo $this->result['votesCount']; ?></div>
            </div>
            <div class="descriptionItem">
                <div class="title">Category</div>
                <div class="value"><? echo $this->result['siteCategory']; ?></div>
            </div>
            <div class="websiteDateAdded">
                <div class="title">Added on</div>
                <div class="value"><? echo $this->result['dateAdded']; ?></div>
            </div>
            <? if ($isSite && $this->hasLoggedUser()): ?>
                <button id="addReviewOpener" class="blueButton">Add a review!</button>

                <div id="reviewAddForm">
                    <table width="500" border="0">
                        <tbody>
                            <tr>
                                <td width="300" rowspan="6"><textarea name="comment" id="comment" placeholder="insert your comment here!! :)"  maxlength="200" lengthcut="true"></textarea></td>
                                <td colspan="2"><div align="center" class="titleVal">Reliability</div></td>
                            </tr>
                            <tr>
                                <td width="63"><div id="reliabilityVal" align="center" class="voteVal">5.0</div></td>
                                <td width="115"><input name="reliability" type="range" min="0.0" max="10.0" step="0.1" id="reliabilitySlider" value="5.0"/></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div align="center"  class="titleVal">Usability</div></td>
                            </tr>
                            <tr>
                                <td><div id="usabilityVal" align="center" class="voteVal">5.0</div></td>
                                <td><input name="usability" type="range" min="0.0" max="10.0" step="0.1" id="usabilitySlider" value="5.0"/></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div  align="center"  class="titleVal">Contents</div></td>
                            </tr>
                            <tr>
                                <td><div id="contentsVal" align="center" class="voteVal">5.0</div></td>
                                <td><input name="contents" type="range" min="0.0" max="10.0" step="0.1" id="contentsSlider" value="5.0"/></td>
                            </tr>
                        </tbody>
                      
                    </table>
                     <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
                            <button class="greenButton" id="addReviewSendButton">Add Review!</button>
                            <p id="reviewResult"></p>
                </div>           
            <? endif; ?>
        </div>
   
    
    <? else : ?>

        <div class="websiteContainer">
            <div class="websiteName"><a><? echo $this->result['userName']; ?></a></div>
            <div class="descriptionItem important">
                <div class="title">Average vote</div>
                <div class="value"><? echo $this->result['averageVote']; ?></div>
            </div>
            <div class="descriptionItem">
                <div class="title">Number of reviews</div>
                <div class="value"><? echo $this->result['votesCount']; ?></div>
            </div>
            <div class="websiteDateAdded">
                <div class="title">User since</div>
                <div class="value"><time><? echo $this->result['userFrom']; ?></time></div>
            </div>
        </div>  

    <? endif; ?>
</div>

<style>
   .reviewContainer{
        width:600px;
        margin-left:auto;
        margin-right:auto;
        margin-top: 20px;
        padding-left: 30px;
        padding-right: 30px;
        padding-bottom: 00px;
        background-color: rgb(245,245,245);
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        border: 1px solid #e8e8e8;
}

.reviewContainer .r_titleBox{
        height:50px;
        line-height:50px;
        width:inherit;
        margin:0px;
        border-bottom: 1px solid rgb(230,230,230);
}

.reviewContainer .r_titleBox .r_mainVoteContainer{
        width:60px;
        height:inherit;
        line-height:inherit;
        float:left;
        margin-left:5px;
}
.reviewContainer .r_titleBox .r_mainVoteContainer div.mainVote{
        text-align:left;
        width:inherit;
        height:inherit;
        line-height:inherit;
        font-size:30px;
        font-weight:bold;
		
}
.reviewContainer .r_titleBox .titleContainer{
        height:inherit;
        float:left;
        max-width:420px;
        min-width:50px;
        max-height:50px;
        display:inline-block;
        line-height:50px;
        margin-left:10px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;

}
.reviewContainer .r_titleBox .reviewDate{
        float:right;
}
.reviewContainer .votesContainer{
        width:inherit;
        height:30px;/*da cambiare con line-height*/
        line-height:30px;
        margin:0px;
        border-bottom: 1px solid rgb(230,230,230);
}

.reviewContainer .votesContainer .r_voteItem{
        width:200px;
        height:inherit;
        display:block;
        float:left;
        margin:0px;
        padding:0px;
}
.reviewContainer .votesContainer .r_voteItem .r_voteValue{
        display:inline;
        margin-left:35px;
        font-size: 16px;
        font-weight: bold;
}
.reviewContainer .votesContainer .r_voteItem .r_voteLabel{
        display:inline;
        margin-left:10px;
        font-weight: 200;
        font-size: 14px;
        letter-spacing:1px;
}

.reviewContainer .reviewTextConteiner{
        margin:0px;
        
}
.reviewContainer .reviewTextConteiner p{
        text-align:center;
        padding-left:10px;
        padding-right:10px;
        margin-top: 20px;
        display:block;
        font-size: 16px;
        font-family: "Arial";
} 
    
</style>

<div id="reviewsBox">
    <? foreach ($this->result["reviews"] as $review): ?>
    
       <article>
        <div class="reviewContainer">
            <div class="r_titleBox">
                <div class="r_mainVoteContainer">
                    <div class="reviewVote mainVote"><? echo $review["vote"]; ?></div>
                </div>
                <div class="titleContainer">
                    <? if ($isSite) : ?>
                        <a href="/sites/user?id=<? echo $review["userId"]; ?>"><? echo $review["user"]; ?></a>
                    <? else : ?>
                        <a href="/sites/site?id=<? echo $review["siteId"]; ?>"><? echo $review["site"]; ?></a>
                    <? endif; ?>
                </div>
                <div class="reviewDate"><? echo $review["date_added"]; ?></div>
                
            </div>
            <div class="votesContainer">
                <div class="r_voteItem">
                    <div class="r_voteValue reviewVote"><? echo $review['reliability']; ?></div>
                    <div class="r_voteLabel">Reliability</div>
                </div>
                <div class="r_voteItem">
                    <div class="r_voteValue reviewVote"><? echo $review['usability']; ?></div>
                    <div class="r_voteLabel">Usability</div>
                </div>
                <div class="r_voteItem">
                    <div class="r_voteValue reviewVote"><? echo $review['contents']; ?></div>
                    <div class="r_voteLabel">Contents</div>
                </div>
            </div>
            <div class="reviewTextConteiner">
                <p><? echo $review["comment"]; ?></p>
            </div>
            <? if ($isSite) : ?>
                <!-- <div data-revid="<? echo $review['reviewId']; ?>" class="reportSpam">Report as Spam</div>-->
            <? endif; ?>
        </div>
      </article>

    <? endforeach; ?>
</div>
<button id="moreReviews" class="orangeButton">More</button>
<div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
<? if(PCConfigManager::sharedManager()->getBoolValue('SHOW_AD_ON_SITES')): ?>
<div class="bannerContainer">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Banner pagina sito WebSherpa -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-7297125817538961"
     data-ad-slot="5687707634"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<? endif;?>
<div class="popupOverlay" id="segnalazionePopup">
    <div class="popupBox" style="margin-left:-200px;margin-top:-100px;width:400px;height:250px;"> <a class="closePopup" href="#">close</a>
        <textarea id="testoSegnalazione" placeholder="Why this review should be considered spam?" maxlength="200"></textarea>
        <p id="resultSegnalazione"></p>
        <button id="sendSegnalazioneButton" class="redButton">Report</button>
        <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
    </div>
</div>
