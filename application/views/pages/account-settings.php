 <!-- 主要容器 -->
    <div class="ts narrow container">
        <div class="ts relaxed grid">
            <!-- 標題欄位 -->
            <div class="ts fluid dashed slate">
                <i class="battery high icon"></i>
                <i class="lightning symbol icon"></i>
                <span class="header">學習ＰＯＷＥＲ</span>
                <span class="description">從這裡快速檢視您的學習能量。</span>
                <div class="action">
                    <button class="ts primary pulsing button" id='pulsingBtn'>貝果開門</button>
                </div>
            </div>
            <!-- / 標題欄位 -->
            </div>
            <!-- / 大略卡片欄位 -->
            <!-- 右側雜項欄位 -->
            <div class="floated sixteen wide column">
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
            <div class="floated sixteen wide column">
                <div class="ts top attached info segment">
                    <div class="ts large header">已購買的課程</div>
                </div>
                <div class="ts bottom attached three cards" id="bought_tutorials_section"></div>
            </div>
            <div class="floated sixteen wide column">
                <div class="ts top attached info segment">
                    <div class="ts large header">您開放的課程</div>
                </div>
                <div class="ts bottom attached three cards" id="opened_tutorials_section"></div>
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
    ts('.left.sidebar').sidebar({
        dimPage: true,
        scrollLock: true
    }).sidebar('attach events', '#pulsingBtn','toggle');
</script>