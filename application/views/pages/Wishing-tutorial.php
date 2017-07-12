
<div class="ts narrow container">
    <div class="ts big fluid dashed insetted slate">
        <span class="header">心想課成，百試可樂</span>
        <span class="description">兩岸學聲啼不住，夜半鐘聲大平臺</span>
    </div>
    <!-- <div class="ts icon fluid input">
        <input id="keyword_input" type="text" placeholder="搜尋..." maxlength="32">
        <i class="inverted circular search link icon" id='searchBtn'></i>
    </div> -->
    <br><br>
    <button class="ts fluid positive basic button" id="btnLearn">我想學</button>
    <br><br>
    <div class="ts three cards" id="cards_section">
    </div>
    <br><br>
    <button class="ts fluid primary basic button" onclick="loadMore();">載入更多</button>
</div>
<script>
//Sweet Alert
document.querySelector('#btnLearn').onclick = function(){
    swal({
      title: '說說您的心願',
      html:
        '<div class="ts form">'+
        '<div class="inline field">'+
        '<label>我想學：</label>'+
        '<input id="TITLE" name="TITLE" type="text" placeholder="想學的才藝技能"></div>'+
        '<div class="two fields">'+
        '<div class="field">'+
        '<label>課程程度：</label>'+
        '<select id="LEVEL" name="LEVEL" class="ts basic primary dropdown">'+
        '<option value="入門">入門</option>'+
        '<option value="進階">進階</option>'+
        '<option value="專業">專業</option></select></div>'+
        '<div class="field">'+
        '<label>類別：</label>'+
        '<select id="CATEGORY" name="CATEGORY" class="ts basic positive dropdown">'+
        '<option value="手作">手作</option>'+
        '<option value="語言">語言</option>'+
        '<option value="科技">科技</option>'+
        '<option value="程式">程式</option>'+
        '<option value="商業">商業</option>'+
        '<option value="生活">生活</option>'+
        '<option value="運動">運動</option>'+
        '<option value="其他">其他</option></select></div>'+
        '</div>'+
        '<div class="field">'+
        '<label>期望的課程內容描述</label>'+
        '<textarea id="SHORT_INTRO" name="SHORT_INTRO" rows="3"></textarea></div>'+
        '<div class="inline field">'+
        '<label>推薦教師：</label>'+
        '<input id="TEACHER" name="TEACHER" type="text" placeholder="推薦人選"></div>'+
        '</div>'
        ,//animation: false,
        //customClass: 'animated tada',
      preConfirm: function () {
        return new Promise(function (resolve) {
          submitNewWishing();
          searchWishing();
          resolve([
            $('#swal-input1').val(),
            $('#swal-input2').val()
          ])
        })
      },
      // inputValidator: function (value) {
      //   return new Promise(function (resolve, reject) {
      //     if ( == true) {
      //       resolve()
      //     } else {
      //       reject('所有欄位盡量填寫喔!')
      //     }
      //   })
      // },
      onOpen: function () {
        $('#TITLE').focus()
      }
    }).then(function (result) {
      swal({
        type: 'success',
        title: 'Good job!'
      })
    }).catch(swal.noop)
};
    function submitNewWishing(){
        var post_data = gatherAllInfo();
        if(post_data === false) return;
        SimpleMsgHandler.handleRequest(true, TutorialController.submitNewWishing(post_data), function(){
            // alert('課程新增成功,待官方人員核准就會進入募資階段!');
        });
    }
    function gatherAllInfo(){
        var output = {};
        output['TITLE']=$('#TITLE').val();
        output['LEVEL']=$('#LEVEL').val();
        output['CATEGORY']=$('#CATEGORY').val();
        output['SHORT_INTRO']=$('#SHORT_INTRO').val();
        output['TEACHER']=$('#TEACHER').val();
        output['DESIRE']='0';
        output['EYES_COUNT']='0';
        return output;
    }
    var subtab_idx = 0;
    var keyword = '';

    $(function(){//頁面載入時即執行的方法
        requestAndRender();
        $('#keyword_input').keyup(function(e){
            if(e.keyCode == 13){
                searchWishing();
            }
        });
    });
    function searchWishing(){
        keyword = $('#keyword_input').val();
        subtab_idx = 0;
        $('#cards_section').empty();//clear the icon section
        requestAndRender();
    }
    function requestAndRender(){
        SimpleMsgHandler.handleRequest(true, TutorialController.searchForWishing(keyword, subtab_idx), function(resp){
            var rows = resp.data;
            rows.reverse();//倒轉陣列
            //add to icon section
            for(var i=0;i<rows.length;++i){
                var str = TutorialIconUtil.generateCard(rows[i]);
                $('#cards_section').append(str);
            }
            checkLikeStatus();
        });        
        return false;
    }
    //測試 案狀狀態 wish id
    function checkLikeStatus(){
        var rowsLiked = <?php echo json_encode($user_data['wishLikeList']);?>;
        for(var i=0;i<rowsLiked.length;++i){
            var str = '#'+rowsLiked[i];
            $(str).find('i.thumbs.up').addClass('negative');
            // console.log(str);
        }
    }
    // end測試
    function loadMore(){
        ++subtab_idx;        
        requestAndRender();
    }
    
    $('#searchBtn').click(function(){
        searchWishing();
    }); 
    $('#cards_section').on('click','.btnDesire',function(){
        var cardId = $(this).attr('id');
        var userAct = "<?php echo $user_data['ACCOUNT'];?>";
        SimpleMsgHandler.handleRequest(true, TutorialController.likeWishing(cardId,userAct), function(resp){
            console.log(JSON.stringify(resp));
        });
        var cnt = $(this).find('.label').text();
        if($(this).find('i.thumbs.up').hasClass('negative')){
            --cnt;
        }else{
            ++cnt;
        }
        $(this).find('.label').text(cnt);
        $(this).find('i.thumbs.up').toggleClass('negative');
    });
</script>