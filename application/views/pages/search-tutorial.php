<div style="height: 100%;">
    <div class="fill height wrapper">
        <div class="ts narrow container">
            <div class="ts icon fluid input">
                <input id="keyword_input" type="text" placeholder="搜尋..." maxlength="32">
                <i class="inverted circular search link icon" id='searchBtn'></i>
            </div>
            <br><br>
            <div class="ts three waterfall cards" id="cards_section">
            </div>
            <br><br>
            <button class="ts fluid primary basic button" onclick="loadMore();">載入更多</button>
        </div>
    </div>
</div>
<script>
    var subtab_idx = 0;
    var keyword = '';
    function searchRaisingFundTutorial(){
        keyword = $('#keyword_input').val();
        subtab_idx = 0;
        //clear the icon section
        $('#icon_section').empty();
        requestAndRender();
    }
    function requestAndRender(){
        SimpleMsgHandler.handleRequest(true, TutorialController.searchForTutorial('started', keyword, subtab_idx), function(resp){
            var rows = resp.data;
            //add to icon section
            for(var i=0;i<rows.length;++i){
                var str = TutorialIconUtil.generateHtml(rows[i]);
                $('#icon_section').append(str);
            }
        });
        return false;
    }
    function loadMore(){
        ++subtab_idx;
        requestAndRender();
    }
    $(function(){
        $('#keyword_input').keyup(function(e){
            if(e.keyCode == 13){
                searchRaisingFundTutorial();
            }
        });
        requestAndRender();
    });
</script>