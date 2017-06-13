<!DOCTYPE HTML>
<html data-ts-native>
<head>
  <title>
    <?php echo $title ?>
  </title>
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Constant Declarations -->
  <script>
    const BASE_URL = '<?php echo base_url();?>';
  </script>
  
  <!-- 其他JS Function-->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
  <script src="<?php echo base_url();?>asset/js/controllers.js"></script>
  <script type="text/javascript" charset="utf8" src="<?php echo base_url();?>asset/js/util.js"></script>
  <!-- MultiDatesPicker for jQuery UI http://dubrox.github.io/Multiple-Dates-Picker-for-jQuery-UI/#method-removeDates-->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
  <script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js" type="text/javascript"></script>
  <!-- Sweet Alert v2 CSS or JS -->
  <script type="text/javascript" charset="utf8" src="<?php echo base_url();?>asset/js/sweetalert2.min.js"></script>
  <!-- A cross-browser library of CSS animations. -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css"> -->
  <!-- MultiDatesPicker -->
  <link href="https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css" rel="stylesheet"></link>
  <link href="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.css" rel="stylesheet"></link>
  <!-- Sweet Alert v2 CSS or JS -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/sweetalert2.min.css">
  <!-- Tocas UI CSS or JS -->
  <link rel="stylesheet" href="<?php echo base_url();?>asset/css/tocas.css">
  <script type="text/javascript" charset="utf8" src="<?php echo base_url();?>asset/js/tocas.dev.js"></script>
  <!-- <link href="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.1/dist/tocas.css" rel='stylesheet'>
  <script src="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.1/dist/tocas.js"></script> -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
  <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script> -->
  
  <!-- Customized CSS or JS -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/waiting.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/custom.css">
  
  <!-- Simple Markdown Editor 所搭配的基本文字編輯器 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
  <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
  <!-- Theme included stylesheets -->
  <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.3.0/styles/monokai-sublime.min.css" rel="stylesheet">
  <link href="//cdnjs.cloudflare.com/ajax/libs/KaTeX/0.6.0/katex.min.css" rel="stylesheet">
  <link href="//cdn.quilljs.com/1.2.6/quill.snow.css" rel="stylesheet">
  <link href="//cdn.quilljs.com/1.2.6/quill.bubble.css" rel="stylesheet">
</head>
<body>