<div class="ts narrow container">
    <div class="ts icon fluid input">
        <input id="keyword_input" type="text" placeholder="搜尋..." maxlength="32">
        <i class="inverted circular search link icon" id='searchBtn'></i>
    </div>
    <div class="ts labeled icon evenly divided menu" id='searchCategoryBar'>
        <a class="active item">
            <i class="grid layout primary icon"></i> 全部
        </a>
        <a class="item" value="科技">
            <i class="desktop icon"></i> 科技
        </a>
        <a class="item" value="生活">
            <i class="food icon"></i> 生活
        </a>
        <a class="item" value="運動">
            <i class="child icon"></i> 運動
        </a>
        <a class="item" value="手作">
            <i class="send icon"></i> 手作
        </a>
        <a class="item" value="程式">
            <i class="keyboard icon"></i> 程式
        </a>
        <a class="item" value="語言">
            <i class="translate icon"></i> 語言
        </a>
        <a class="item" value="商業">
            <i class="currency icon"></i> 商業
        </a>
        <a class="item" value="其他">
            <i class="usb icon"></i> 其他
        </a>
    </div>
    <br><br>
    <div class="ts three cards" id="icon_section">
    </div>
    <br><br>
    <button class="ts fluid primary basic button" onclick="loadMore();">載入更多</button>
</div>
<script>
    var subtab_idx = 0;
    var keyword = '';
    $('#searchBtn').on('click',function(){
        searchRaisingFundTutorial();
    });
    function searchRaisingFundTutorial(){
        keyword = $('#keyword_input').val();
        subtab_idx = 0;
        //clear the icon section
        $('#icon_section').empty();
        requestAndRender();
    }
    function requestAndRender(){
        SimpleMsgHandler.handleRequest(true, TutorialController.searchForTutorial('raising_funds', keyword, subtab_idx), function(resp){
            var rows = resp.data;
            rows.reverse();//倒轉陣列
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
    //menu效果
    $('#searchCategoryBar .item').on('click',function(){
        $('#searchCategoryBar a').removeClass('active');
        $(this).addClass('active');
        keyword = $(this).attr("value");
        subtab_idx = 0;
        $('#icon_section').empty();
        requestAndRender();
    });
    //menu效果 END
</script>