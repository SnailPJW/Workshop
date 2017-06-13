<!-- 主要容器網格系統 -->
    <div class="ts narrow container grid">
        <!-- 左側欄位 -->
        <div class="twelve wide column">
            <div id="tabMsg" class="ts top attached tabbed menu">
                <a class="active item"  data-tab="未讀">
                    <div class="ts label">1</div>
                    未讀
                </a>
                <a class="item" data-tab="已讀">
                    <div class="ts label">1</div>
                    已讀
                </a>
            </div>
            <div data-tab="未讀" class="ts active bottom attached tab segment">
                <!-- 未讀內容！ -->
                <div id="not_read" class="tab-pane fade in active">
                  
                </div>
            </div>
            <div data-tab="已讀" class="ts bottom attached tab segment">
                <!-- 已讀內容！ -->
                <div id="read" class="tab-pane fade">
                  
                </div>
            </div>
            <div class="ts primary message">
                <div class="header">訊息</div>
                <p id="msg_content"></p>
            </div>
        </div>
    </div>
<!-- 
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 pull-right onsitemsg-panel">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#not_read">未讀訊息</a></li>
                <li><a data-toggle="tab" href="#read">已讀訊息</a></li>
              </ul>
              <div class="tab-content">
                <div id="not_read" class="tab-pane fade in active">
                  
                </div>
                <div id="read" class="tab-pane fade">
                  
                </div>
              </div>
        </div>
        <div class="col-sm-8">
            <h1 id="msg_title"></h1>
            <textarea id="msg_content" rows="10" style="width:100%;" class="form-control" readonly></textarea>
        </div>
    </div>
</div> -->
<script>
    ts('#tabMsg.tabbed.menu .item').tab({
        onSwitch: (tabName, groupName) => {
            //alert("你切換到了「" + tabName + "」分頁，而群組是「" + groupName + "」。");
        }
    });

    var msgs = <?php echo json_encode($on_site_msgs);?>//msg_id to data map
    function loadMsgs(){
        for(var id in msgs){
            if(msgs.hasOwnProperty(id)){
                var m = msgs[id];
                if(m.READ_BY_USER=="0"){
                    $('#not_read').prepend('<button class="btn btn-warning on-site-msg-btn unread_btn" msg_id="'+id+'">'+m.TITLE+'('+m.CREATE_TIME+')</button>');
                }else{
                    $('#read').prepend('<button class="btn btn-warning on-site-msg-btn" msg_id="'+id+'">'+m.TITLE+'('+m.CREATE_TIME+')</button>');
                }
            }
        }
    }
    $(function(){
        loadMsgs();
        $(document).on('click', '.unread_btn', function(){
            var id = $(this).attr('msg_id');
            GenericController.post('sitemsg','markAsRead',{'MSG_ID':id});
        });
        $(document).on('click', '.on-site-msg-btn', function(){
           var id = $(this).attr('msg_id');
           var m = msgs[id];
           $('#msg_title').empty();
           $('#msg_title').text(m.TITLE);
           $('#msg_content').val(m.MSG);
        });
    });
</script>