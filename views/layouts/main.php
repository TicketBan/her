<?php
use yii\helpers\Html;
?>
    <!DOCTYPE html>
    <html lang="ru">
    <?php $this->beginPage() ?>
    <head>
        <?php $this->head() ?>
        <meta charset="utf-8">
        <meta name="robots" content="nofollow" />
        <?= Html::csrfMetaTags() ?>
        <title><?php if (!empty($this->params['meta_title'])) { echo $this->params['meta_title']; } ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="twitter:card" content="summary_large_image" />

        <meta property="vk:image" content="<?php if(!empty($this->params['meta_image'])) { echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->params['meta_image']; } ?>" />
        <meta property="twitter:image" content="<?php if(!empty($this->params['meta_image'])) { echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->params['meta_image']; } ?>" />
        <meta property="og:image" content="<?php if(!empty($this->params['meta_image'])) { echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->params['meta_image']; } ?>" />
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="315" />
        <meta property="og:title" content="<?php if (!empty($this->params['meta_title'])) { echo $this->params['meta_title']; } ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="<?php if (!empty($this->params['meta_text'])) { echo mb_substr(strip_tags($this->params['meta_text']), 0, 110); } ?>" />
        <meta name="description" content="<?php if (!empty($this->params['meta_text'])) { echo mb_substr(strip_tags($this->params['meta_text']), 0, 110); } ?>" />
        <meta name="author" content="<?php if (!empty($this->params['meta_author'])) { echo $this->params['meta_author']; } ?>" />

        <link rel="image_src" href="<?php if(!empty($this->params['meta_image'])) { echo 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->params['meta_image']; } ?>">

        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/sass/main.css?<?php echo rand(1,99999);?>">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/css/medium-editor-insert-plugin.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/medium-editor.min.css "/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/beagle.css "/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script>
            var __xsrf = "<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>";

            function placeCaretAtEnd(el) {
                el.focus();
                if (typeof window.getSelection != "undefined"
                    && typeof document.createRange != "undefined") {
                    var range = document.createRange();
                    range.selectNodeContents(el);
                    range.collapse(false);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else if (typeof document.body.createTextRange != "undefined") {
                    var textRange = document.body.createTextRange();
                    textRange.moveToElementText(el);
                    textRange.collapse(false);
                    textRange.select();
                }
            }
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.runtime.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sortable/0.9.13/jquery-sortable-min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery.ui.widget@1.10.3/jquery.ui.widget.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.iframe-transport/1.0.1/jquery.iframe-transport.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.28.0/js/jquery.fileupload.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/js/medium-editor.min.js"></script>
        <script src="assets/js/medium-editor-insert-plugin.min.js?<?php echo rand(1111,231231231); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>


        <style>
            @media all and (min-width: 767px) {
                .medium-insert-buttons {
                    left: -20px !important;
                }
            }

            .medium-editor-insert-plugin.medium-editor-placeholder:after {
                padding: 0px;
                margin: 0px 21px;
                color: #777 !important;
                font-style: normal;
            }

            .medium-insert-embeds-overlay {
                display: none !important;
            }

            figcaption {
                width: 160px;
                margin: auto !important;
            }

            .medium-insert-images-grid figure {
                width: auto;
                flex: 1;
            }

        </style>
    <head>
    <body>
    <?php $this->beginBody() ?>
    <?= $content; ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>