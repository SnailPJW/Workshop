<!-- 主要容器網格系統 -->
    <div class="ts narrow container grid">
        <!-- 左側欄位 -->
        <div class="twelve wide column">
            <br>
            <!-- 頂部聊天室資訊欄位 -->
            <div class="ts top attached segmented small single line items">
                <div class="item">
                    <div class="ts mini image">
                        <img src="../../../asset//img/user.png">
                    </div>
                    <div class="content">
                        <div class="header">可愛課訴Robot</div>
                        <div class="meta">
                            <div>@Workshop.NTUT</div>
                        </div>
                    </div>
                    <div class="actions">
                        <div class="ts secondary icon button">
                            <i class="vertical ellipsis icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / 頂部聊天室資訊欄位 -->

            <!-- 中部主要聊天訊息 -->
            <div class="ts attached secondary segment">
                <!-- 對話氣泡 -->
                <div class="ts speeches">
                    <!-- 左側群組 -->
                    <div class="circular group">
                        <div class="speech">
                            <div class="avatar">
                                <img src="../../../asset//img/user.png">
                            </div>
                            <div class="content">喔！你醒了啊？</div>
                        </div>
                        <div class="speech">
                            <div class="avatar">
                                <img src="../../../asset//img/user.png">
                            </div>
                            <div class="content">我已經連續兩天沒休息了。</div>
                        </div>
                        <div class="speech">
                            <div class="avatar">
                                <img src="../../../asset//img/user.png">
                            </div>
                            <div class="content">猜猜我在做什麼？</div>
                        </div>
                    </div>
                    <!-- / 左側群組 -->

                    <!-- 右側群組 -->
                    <div class="right circular group">
                        <div class="speech">
                            <div class="avatar">
                                <img src="../../../asset//img/user2.png">
                            </div>
                            <div class="content">額⋯⋯</div>
                        </div>
                        <div class="speech">
                            <div class="avatar">
                                <img src="../../../asset//img/user2.png">
                            </div>
                            <div class="content">跟我聊天？</div>
                        </div>
                    </div>
                    <!-- / 右側群組 -->
                </div>
                <!-- / 對話氣泡 -->
            </div>
            <!-- / 中部主要聊天訊息 -->

            <!-- 底部輸入欄位 -->
            <div class="ts bottom attached segment">
                <div class="ts grid">
                    <div class="stretched column">
                        <div class="ts fluid input">
                            <textarea rows="5" maxlength="1024" id="comment" placeholder="敘述你所遭遇的問題......"></textarea>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ts button" onclick="submitProblem();">送出</div>
                    </div>
                </div>
            </div>
            <!-- / 底部輸入欄位 -->
        </div>
        <!-- / 左側欄位 -->

        <!-- 右側欄位 -->
        <div class="four wide column">
            <br>
            <div class="ts tiny segmented single line items">
                <div class="selected item">
                    <div class="ts mini image">
                        <img src="../../../asset//img/user.png">
                    </div>
                    <div class="content">
                        <div class="header">可愛課訴Robot</div>
                        <div class="meta">
                            <div>@Workshop.NTUT</div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="ts mini image">
                        <img src="../../../asset//img/user2.png">
                    </div>
                    <div class="content">
                        <div class="header">測試版型</div>
                        <div class="meta">
                            <div>@尚未成功.仍須努力</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / 右側欄位 -->
    </div>
<!-- / 主要容器網格系統 -->

<script>
    function submitProblem(){
        var description = $('#comment').val();
        SimpleMsgHandler.handleRequest(true, GenericController.post('tickets','submitTicket',{'DESCRIPTION':description}), function(resp){
            alert(resp.data);
        });
    }
</script>