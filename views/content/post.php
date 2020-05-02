<div class="wrapper">
    <div class="btn-edit"><a href="#" class="content-save">EDIT</a></div>
    <div class="btn-edit"><a href="#" class="content-publish">PUBLISH</a></div>
    <div class="btn-new"><a href="/" class="content-new">New post</a></div>
    <header>
        <h1 <?php if(empty($title)) { ?> contentEditable="true" <?php } ?> data-text="Заголовок"><?php if(!empty($title)) echo $title; ?></h1>
        <address>
            <div class="author" <?php if(empty($author)) { ?>contentEditable="true" <?php } ?> data-text="Автор"><?php if(!empty($author)) echo $author; ?></div>
            <time></time>
        </address>
    </header>
    <article <?php if(empty($text)) { ?> contentEditable="true" <?php } ?> data-text="Текст" class="article">
        <?php if(!empty($text)) {
            echo $text;
        } else {
            echo "<p class='medium-insert-active'><br></p>";
        } ?>
    </article>
    <aside>
        <div class="btn-edit"><a href="#" class="content-save">EDIT</a></div>
        <div class="btn-edit"><a href="#" class="content-publish">PUBLISH</a></div>
        <div class="btn-new"><a href="/" class="content-new">New post</a></div>
    </aside>
</div>

<script>
    var url_path = window.location.pathname;
    var page_id = '<?php if(!empty($page_id)) echo $page_id; ?>',
        upload_files = [],
        save_mode = 0,
        title_editor, author_editor, article_editor;

    var app = {
        convertDate: function (time) {
            if (time.length < 1)
                return;

            time = new Date(time * 1000);

            var month = 0;
            switch (time.getMonth()) {
                case 0: month = 'Jan'; break;
                case 1: month = 'Feb'; break;
                case 2: month = 'Mar'; break;
                case 3: month = 'Apr'; break;
                case 4: month = 'May'; break;
                case 5: month = 'Jun'; break;
                case 6: month = 'Jul'; break;
                case 7: month = 'Aug'; break;
                case 8: month = 'Sep'; break;
                case 9: month = 'Oct'; break;
                case 10: month = 'Nov'; break;
                case 11: month = 'Dec'; break;
            }

            var ret_date = time.getDate() + ' ' + month + ' ' + time.getFullYear();
            return ret_date;
        },

        checkSession: function (uid) {
            $.ajax({
                url: '/post/checkSession',
                type: 'post',
                data: "uid=" + uid + '&page_id=' + page_id,
                beforeSend: function () {
                    console.log('Check session...');
                },
                success: function (data) {
                    var data = JSON.parse(data);
                    console.log(data.checked);
                    if (data.checked == 'true') {
                        $('.content-save').css({'display': 'inline'});
                        $('.content-new').css({'display': 'flex'});
                    } else {
                        $('.content-save').css({'display': 'none'});
                        $('.content-new').css({'display': 'none'});
                    }
                }
            });
        },

        getSession: function () {
            var uid;
            $.ajax({
                url: '/post/getSession',
                type: 'post',
                beforeSend: function () {
                    console.log('Check session...');
                },
                success: function (data) {
                    var data = JSON.parse(data);
                        uid = data.uid;

                    Cookies.set('uid', uid);
                }
            });
        },
    }

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": "<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>"
            }
        });


        function resizeYoutube () {

            setTimeout(function () {
                // Find all YouTube videos
                var $allVideos = $("iframe[src^='//www.youtube.com']"),
                    $fluidEl = $("article");

                $allVideos.each(function() {

                    $(this)
                        .data('aspectRatio', this.height / this.width)

                        .removeAttr('height')
                        .removeAttr('width');

                });

                var newWidth = $fluidEl.width();

                $allVideos.each(function() {

                    var $el = $(this);
                    $el
                        .width(newWidth)
                        .height(newWidth * (0.56));

                });

            }, 500);

        }

        resizeYoutube();

        var uid = Cookies.get('uid');

        $('article').on('keypress', 'figcaption', function (e) {
            var keycode = e.charCode || e.keyCode;

            if (keycode == 13) {
                if (this.nodeName == 'FIGCAPTION') {
                    //placeCaretAtEnd($('article').get(0));
                }
            }
        });

        if (url_path == '/') {
            $('.content-publish').css({'display': 'inline'});
            $('.content-save').css({'display': 'none'});

            if (uid == undefined)
                app.getSession();

            saveMode(true);
        }

        if (uid == undefined) {
            app.getSession();
            $('.content-publish').css({'display':'inline'});
            $('.content-save').css({'display':'none'});
        } else {
            if (page_id.length > 6)
                app.checkSession(uid);
        }

        $('.content-publish').click(function () {

            var data = {};

            data['title'] = $('h1').text();
            data['author'] = $('.author').text();

            $('.medium-insert-buttons').remove();
            data['text'] = article_editor.getContent();

            $.ajax({
                url: '/post/publish',
                type: 'post',
                data: "title=" + data['title'] + "&author=" + data['author'] + "&text=" + data['text'] + "&uid=" + uid,
                beforeSend: function () {
                    console.log('Save...');
                },
                success: function (data) {
                    var data = JSON.parse(data);

                    if (data.url != undefined)
                        window.location = '/@' + data.url;
                    else
                        Swal.fire({
                            title: data.error,
                            type: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        });
                }
            });
        });

        $('.content-save').click(function () {
            if (save_mode) {
                var data = {};
                data['title'] = $('h1').text();
                data['author'] = $('.author').text();

                $('.medium-insert-buttons').remove();
                data['text'] = article_editor.getContent();
                data['page_id'] = page_id;

                $.ajax({
                    url: '/post/update',
                    type: 'post',
                    data: "title=" + data['title'] + "&author=" + data['author'] + "&text=" + data['text'] + "&page_id=" + data['page_id'] + "&uid=" + uid,
                    contentType: 'application/x-www-form-urlencoded',
                    beforeSend: function () {
                        console.log('Update...');
                        saveMode(false);
                        $('.content-save').text('EDIT');
                        save_mode = 0;
                    },
                    success: function (data) {
                        var data = JSON.parse(data);
                        console.log('Success update..');
                    }
                });

                return true;
            } else {
                saveMode(true);
                $('.content-save').text('PUBLISH');
                save_mode = 1;
                return true;
            }
        });

    });

    /* editable */

    function saveMode(state) {
        if (title_editor !== undefined) {
            title_editor.destroy();
            author_editor.destroy();
            article_editor.destroy();
        }

        if (state)  {
            title_editor = new MediumEditor('h1', {
                placeholder: false,
                disableReturn: true,
                disableDoubleReturn: true,
                spellcheck: false,
                paste: {
                    forcePlainText: true,
                    cleanPastedHTML: true,
                    unwrapTags: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'article'],
                    cleanAttrs: ['class', 'style', 'dir']
                },
                toolbar: {
                    buttons: ['bold', 'italic', 'underline'],
                },
            });

            author_editor = new MediumEditor('.author', {
                placeholder: false,
                disableReturn: true,
                disableDoubleReturn: true,
                spellcheck: false,
                paste: {
                    forcePlainText: true,
                    cleanPastedHTML: true,
                    unwrapTags: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'article'],
                    cleanAttrs: ['class', 'style', 'dir']
                },
                toolbar: {
                    buttons: [],
                }
            });

            article_editor = new MediumEditor('article', {
                placeholder: false,
                imageDragging: true,
                spellcheck: false,
                paste: {
                    forcePlainText: true,
                    cleanPastedHTML: true,
                    unwrapTags: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'article'],
                    cleanAttrs: ['class', 'style', 'dir']
                },
                toolbar: {
                    buttons: ['bold', 'italic', 'underline', 'anchor', 'h2', 'h3'],
                },
                placeholder: {
                    text: 'Текст',
                    hideOnClick: true
                }
            });

            $('article').mediumInsert({
                editor: article_editor,
                enabled: true,
                addons: {
                    images: {
                        uploadScript: null,
                        deleteScript: '/content/img/delete',
                        deleteMethod: 'POST',
                        preview: true,
                        captions: true,
                        captionPlaceholder: 'Описание изображения (необязательно)',
                        autoGrid: 2,
                        fileUploadOptions: {
                            url: '/content/img/upload',
                            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/im,
                        },
                        messages: {
                            acceptFileTypesError: 'Это не поддерживаемый формат файла: ',
                            maxFileSizeError: 'Этот файл слишком большой: '
                        },
                        uploadCompleted: function ($el, data) {
                            var file = data.result.files[0].url;
                            upload_files.push(file);
                        },
                        uploadFailed: function (uploadErrors, data) { },
                    },
                    embeds: {
                        oembedProxy: null,
                        placeholder: 'Вставьте ссылку на YouTube, Vimeo, Facebook, Twitter or Instagram и нажмите Enter',
                        styles: {
                            wide: {
                                label: '<span class="fa fa-align-justify"></span>',
                                added: function ($el) {},
                                removed: function ($el) {}
                            },
                            left: {
                                label: '<span class="fa fa-align-left"></span>'
                            },
                            right: {
                                label: '<span class="fa fa-align-right"></span>'
                            }
                        },
                        actions: {
                            remove: {
                                label: '<span class="fa fa-times"></span>',
                                clicked: function ($el) {
                                    var $event = $.Event('keydown');

                                    $event.which = 8;
                                    $(document).trigger($event);
                                }
                            }
                        }
                    }
                },
            });

            save_mode = 1;

            return true;
        } else {
            title_editor.destroy();
            author_editor.destroy();
            article_editor.destroy();

            title_editor = new MediumEditor('h1', { placeholder: false, disableEditing: true });
            author_editor = new MediumEditor('.author', { placeholder: false, disableEditing: true });
            article_editor = new MediumEditor('article', { placeholder: false, disableEditing: true });
            save_mode = 0;

            return true;
        }
    }


    /*** Время ***/
    $('time').text(app.convertDate('<?php if(!empty($time)) echo $time; ?>'));
</script>