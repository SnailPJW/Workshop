 <!-- 左側邊欄 -->
    <div class="ts left vertical fluid inverted visible menu sidebar">
        
        <!-- 個人資料項目 -->
        <div class="center aligned item">
            <img class="ts tiny circular image" id="user_pic" src="<?php echo $user_data['PICTURE_URL']; ?>" style="background-color: white;">
            <br>
            <br>
            <div><?php echo $user_data['NAME'];?></div>
        </div>
        <!-- / 個人資料項目 -->

        <!-- 使用者 -->
        <div class="item">
            <i class="user icon"></i>
            帳戶
            <div class="menu">
                <a class="item"><?php echo $user_data['ACCOUNT'];?></a>
            </div>
        </div>
        <!-- / 使用者 -->
        <!-- 信箱 -->
        <div class="item">
            <i class="mail icon"></i>
            信箱
            <div class="menu">
                <a class="item"><?php echo $user_data['EMAIL'],"<small>"; if($user_data['EMAIL_CONFIRMED']) echo ' (已驗證)';else echo ' (未驗證)';echo "</small>" ?></a>
            </div>
        </div>
        <!-- / 信箱 -->
        <!-- 網站管理 -->
        <div class="item">
            <i class="image icon"></i>
            照片
            <div class="menu">
                <a class="item">上傳
                        <div class="wrapper">
                          <div class="file-upload">
                            <i class="tiny cloud upload icon">
                            <input type="file" id="picture_file_upload" accept="image/*">
                            </i>
                          </div>
                        </div>
                </a>
            </div>
        </div>
        <!-- / 網站管理 -->
    </div>
    <!-- / 左側邊欄 -->

        <!-- 主要容器 -->
        <div class="ts narrow container">
            <div class="ts relaxed grid">
                <!-- 標題欄位 -->
                <div class="right floated fifteen wide column">
                    <h3 class="ts header">
                        學習狀態
                        <div class="sub header">從這裡快速檢視您的學習狀態。</div>
                    </h3>
                </div>
                <!-- / 標題欄位 -->

                <!-- 大略卡片欄位 -->
                <div class="right floated fifteen wide column">
                    <div class="ts two cards">
                        <!-- 本月拜訪次數 -->
                        <div class="ts card">
                            <div class="content">
                                <!-- 統計數據 -->
                                <div class="ts left aligned statistic">
                                    <div class="value">
                                        189
                                        <div class="increment">32</div>
                                    </div>
                                    <div class="label">本月拜訪次數</div>
                                </div>
                                <!-- / 統計數據 -->
                            </div>
                            <div class="symbol">
                                <i class="eye icon"></i>
                            </div>
                        </div>
                        <!-- / 本月拜訪次數 -->
                        <!-- 平均在線分鐘數 -->
                        <div class="ts card">
                            <div class="content">
                                <!-- 統計數據 -->
                                <div class="ts left aligned statistic">
                                    <div class="value">
                                        3
                                        <div class="decrement">14</div>
                                    </div>
                                    <div class="label">平均在線分鐘數</div>
                                </div>
                                <!-- / 統計數據 -->
                            </div>
                            <div class="symbol">
                                <i class="time icon"></i>
                            </div>
                        </div>
                        <!-- / 平均在線分鐘數 -->
                    </div>
                    <!-- 進度列 -->
                    <div class="ts small indeterminate progress">
                        <div class="bar" style="width: 30%"></div>
                    </div>
                    <!-- / 進度列 -->
                </div>
                <!-- / 大略卡片欄位 -->
                <!-- 右側雜項欄位 -->
                <div class="right floated fifteen wide column">
                    <!-- 系統佇列 -->
                    <div class="ts top attached info segment">
                        <div class="ts large header">簡短自我介紹(最多50字)</div>
                    </div>
                    <div class="ts bottom attached segment">
                        <div class="ts underlined fluid circular resizable big positive input">
                            <input type='text' id="short_intro" value="<?php echo $user_data['SHORT_INTRO'];?>"/>
                            <button class="ts primary basic button" onclick="updateShortIntro();">更新</button>
                        </div>
                    </div>
                    <!-- / 系統佇列 -->
                </div>
                <div class="right floated fifteen wide column">
                    <div class="ts top attached info segment">
                        <div class="ts large header">已購買的課程</div>
                    </div>
                    <div class="ts bottom attached segment" id="bought_tutorials_section"></div>
                </div>
                <div class="right floated fifteen wide column">
                    <div class="ts top attached info segment">
                        <div class="ts large header">您開放的課程</div>
                    </div>
                    <div class="ts bottom attached segment" id="opened_tutorials_section"></div>
                </div>
        </div>
        <!-- / 主要容器 -->
</div>
<script>
    $(function(){
        $('#picture_file_upload').change(function(){
            uploadSelectedFile();
        });
    })
    function renderBoughtTutorials(){
        var rows = <?php echo json_encode($user_data['bought_tutorials']);?>;
        for(var i=0;i<rows.length;++i){
            var str = TutorialIconUtil.generateHtml(rows[i]);
            $('#bought_tutorials_section').append(str);
        }
    }
    function renderOpenedTutorials(){
        var rows = <?php echo json_encode($user_data['opened_tutorials']);?>;
        for(var i=0;i<rows.length;++i){
            var str = TutorialIconUtil.generateHtml(rows[i]);
            $('#opened_tutorials_section').append(str);
        }
    }
    function updateShortIntro(){
        var new_intro = $('#short_intro').val();
        AnimationUtil.startWaitingAnimation();
        $.post("<?php echo base_url().'index.php/users/updateShortIntro';?>", {'SHORT_INTRO':new_intro})
            .done(function(data){
                if(data.status == 'success'){
                    $('#short_intro').val(data.data);
                }else{
                    alert(data.data);
                }
            })
            .fail(function(data){
                alert("伺服器錯誤!act");
            })
            .always(function(){AnimationUtil.endWaitingAnimation();})
    }
    function getFileExt(file_name){
        var start = file_name.lastIndexOf('.');
        if(start<0) return "";
        return file_name.substring(start+1);
    }
    function uploadSelectedFile(){
        var files = document.getElementById('picture_file_upload').files;
        if(files==null) return;
        if(files.length==0) return;
        var file = files[0];
        var ext = getFileExt(file.name);
        if(!ext||ext.length==0){ 
            alert('請選擇圖檔!');
            return;
        }
        
        AnimationUtil.startWaitingAnimation();
        $.ajax({
                url: "<?php echo base_url().'index.php/users/uploadPicture';?>",
                type: 'POST',
                processData: false,
                headers: {
                    'File-Extension': ext,
                    'Content-Type': file.type
                },
                data:file,
                async: true,
                success: function(resp) {
                    var url = resp.data;
                    $('#user_pic').attr('src', url);
                    AnimationUtil.endWaitingAnimation();
                },
                error: function(xhr, status, error) {
                    AnimationUtil.endWaitingAnimation();
                }
        });
    }
    $(function(){
        renderBoughtTutorials();
        renderOpenedTutorials();
    })
</script>