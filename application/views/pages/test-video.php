<script>
    
    $(function(){
        tutorialVideoId = 22;
        $('#preview_video').attr('src', VideoController.getVideoUrl(tutorialVideoId));
    });
    
</script>
<video id="preview_video" style='max-width:100%;' controls>
</video>