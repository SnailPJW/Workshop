<!-- 主要容器 -->
    <div class="ts narrow container">
        <br><br>

        <!-- 主要網格系統 -->
        <div class="ts grid">
            <!-- 左側欄位 -->
            <div class="eleven wide column">
                <!-- 檔案目錄清單 -->
                <div class="ts selection segmented list">
                    <a href="#!" class="item">
                        <i class="file outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file image outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file image outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="active item">
                        <i class="file image outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file text outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file archive outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">
                        <i class="file image outline icon"></i>
                        這大概是課訴版型
                    </a>
                    <a href="#!" class="item">..</a>
                </div>
                <!-- / 檔案目錄清單 -->
            </div>
            <!-- / 左側欄位 -->

            <!-- 右側欄位 -->
            <div class="five wide column">
                <div class="ts card">
                    <!-- 檔案圖示 -->
                    <div class="secondary very padded extra content">
                        <div class="ts icon header">
                            <i class="file image outline icon"></i>
                        </div>
                    </div>
                    <!-- / 檔案圖示 -->

                    <!-- 檔名內容 -->
                    <div class="extra content">
                        <div class="header">2017-05-13 下午12.35.11</div>
                    </div>
                    <!-- / 檔名內容 -->

                    <!-- 檔案大綱 -->
                    <div class="extra content">
                        <div class="ts list">
                            <!-- 檔案擁有者 -->
                            <div class="item">
                                <i class="user icon"></i>
                                <div class="content">
                                    <div class="header">課訴人</div>
                                    <div class="description">Yami Odymel</div>
                                </div>
                            </div>
                            <!-- / 檔案擁有者 -->

                            <!-- 檔案驗證碼 -->
                            <div class="item">
                                <i class="lock icon"></i>
                                <div class="content">
                                    <div class="header">課訴內容</div>
                                    <div class="description">20838a8df7cc0babd745c7af4b7d94e2</div>
                                </div>
                            </div>
                            <!-- / 檔案驗證碼 -->
                        </div>
                    </div>
                    <!-- / 檔案大綱 -->
                </div>
            </div>
            <!-- / 右側欄位 -->
        </div>
        <!-- / 主要網格系統 -->
    </div>
<!-- / 主要容器 -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class ="row">
    <div class="col-sm-4 ticket-master-panel pull-right">
        <h1>選擇客戶申訴事項</h1>
        <div id="ticket-master-container">
            <?php 
            if($unanswered_tickets){
                foreach($unanswered_tickets as $ticket){
                    echo '<button class="btn btn-warning btn-lg ticket-button" ticket_id="'.$ticket['TICKET_ID'].'"><span class="glyphicon glyphicon-pencil"></span>  '.html_escape($ticket['DESCRIPTION']).'...</button>';
                }
            }
            ?>
        </div>
    </div>
    <div class="col-sm-8 ticket-content-panel">
        <h1>申訴人</h1>
        <input type="text" id="uname">  
        <h1>申訴內容</h1>
        <textarea id="description" rows="10" readonly>
            
        </textarea>
        <h1>回覆</h1>
        <textarea id="answer_content" rows="10"></textarea>
        <button class="btn btn-lg btn-primary" onclick="answerTicket();">回覆</button>
    </div>
    
</div>
<script>
    <?php 
            //load the unanswered tickets
            if($unanswered_tickets){
                echo 'var tickets = '.json_encode($unanswered_tickets).';';
            }else{
                echo 'var tickets = []';
            }
    ?>
        
    var ticketMap = {};
    var selectedTicketId = 0;
    function answerTicket(){
        if(selectedTicketId>0){
            var post_data = {
                'TICKET_ID': selectedTicketId,
                'ANSWER_CONTENT': $('#answer_content').val()
            };
            SimpleMsgHandler.handleRequest(true, GenericController.post('tickets','answerTicket',post_data), function(resp){
                alert(resp.data);
                $('button[ticket_id="'+selectedTicketId+'"]').remove();
                $('#description').val();
                $('#answer_content').val();
                selectedTicketId = 0;
            });
            
        }
    }
    function loadTicketMap(){
        for(var i=0;i<tickets.length;++i){
            var t = tickets[i];
            ticketMap[t['TICKET_ID']] = t;
        }
    }
    $(function(){
        loadTicketMap();
        $(document).on('click', '.ticket-button', function(){
            var ticket_id = $(this).attr('ticket_id');
            selectedTicketId = ticket_id;
            //新增功能 能夠知道客訴者
            $('#uname').val(ticketMap[ticket_id]['ACCOUNT']);
            $('#description').val(ticketMap[ticket_id]['DESCRIPTION']);
        });
    })
</script>