<div class="niceContainer">
    <div class="campoUrlContainer">
        <h2 class="urlTitle">Add Website</h2>
        <input type="url" id="campoUrl" placeholder="Insert site url here"/>
    </div>

    <div id="inputBox"> 
        <div class="inputRow">
            <label for="category">Category:</label>
            <select id="categorySelect" name="category" class="centered">
            </select>
        </div>
    </div>
    <!--<div id="siteVote">
        <div class="inputRow">
            <label for="reliability">Reliability:</label>
            <input name="reliability" type="range" min="0.0" max="10.0" step="0.1" id="reliabilitySlider" value=""/>
            <p id="reliabilityVal" align="center" class="centered voteVal" >5.0</p>
        </div>
        <div class="inputRow">
            <label for="usability">Usability:</label>
            <input name="usability" type="range" min="0.0" max="10.0" step="0.1" id="usabilitySlider" value="" />
            <p id="usabilityVal" align="center" class="centered voteVal" style="margin-left:36px">5.0</p>
        </div>
        <div class="inputRow">
            <label for="contents">Contents:</label>
            <input name="contents" type="range" min="0.0" max="10.0" step="0.1" id="contentsSlider" value="" />
            <p id="contentsVal" align="center" class="centered voteVal">5.0</p>
        </div>
        <div  class="inputRow" >
            <label for="comment">Comment: </label>
            <label id="counterLabel">(200 chars)</label>
            <textarea name="comment" id="comment" placeholder="insert your comment here!! :)"  maxlength="200" lengthcut="true"></textarea>
        </div>
        <div class="inputRow">
            <button id="sendButton" type="submit" class="greenButton">Add!!</button>
        </div>
        <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
        <div class="inputRow">
            <p id="reviewResult" style="display:none;"></p>
        </div>
    </div>
</div>-->
    <div id="reviewAddForm" style="display: block;">
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
        <button id="sendButton" type="submit" class="greenButton">Add!!</button>
         <p id="reviewResult" style="display:none;"></p>
         <div class="ajaxIndicator"><img src="/public/fresh/img/ajax-loader.gif" height="16" width="16"/></div>
    </div>
</div>
    
