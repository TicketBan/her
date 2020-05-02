<?php
if (empty(\Yii::$app->session->get('user_name')))
    return \Yii::$app->getResponse()->redirect('/login');

use yii\helpers\Html;
?>
    <!DOCTYPE html>
    <html lang="ru">
    <?php $this->beginPage() ?>
    <head>
        <?php $this->head() ?>
        <meta charset="utf-8">
        <?= Html::csrfMetaTags() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/assets/css/animate.css">
        <link rel="stylesheet" href="/assets/css/sass/admin.css?<?php echo rand(1, 999929999); ?>">

        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/css/medium-editor-insert-plugin.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/medium-editor.min.css "/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/beagle.css "/>
        <script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

        <style>
            .medium-insert-buttons {
                left: -20px !important;
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

            .medium-insert-images {
                margin: 0px !important;
                padding: 0px !important
            }

            .medium-insert-active {
                margin: 0px !important;
                padding: 0px !important
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
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <div class="header">
            <div class="profile">
                <a href="/exit">Выйти из профиля</a>
            </div>
        </div>
        <div class="content-panel">
            <ul>
                <li><div class="check" id="check-all" data-check="false"></div> Выделить все</li>
            </ul>
            <div class="panel">
                <a href="#" class="post" id="check-publish"><img src="assets/img/icons/check-mark.svg">Опубликовать</a>
                <a href="#" class="post" id="check-moder"><img src="assets/img/icons/clock.svg">На модерацию</a>
                <a href="#" class="reject" id="check-reject"><img src="assets/img/icons/delete-red.svg">Отклонить</a>
                <a href="#" class="reject" id="check-delete"><img src="assets/img/icons/delete-button.svg">Удалить</a>
            </div>
        </div>
        <div class="wrapper-content">
            <div class="nav">
                <ul>
                    <li><a href="#" class="active" id="get-publish"><img src="assets/img/icons/check-mark.svg">Опубликованы <span id="count-publish">0</span></a></li>
                    <li><a href="#" id="get-moder"><img src="assets/img/icons/clock.svg">На модерации <span id="count-moder">0</span></a></li>
                    <li><a href="#" id="get-reject"><img src="assets/img/icons/delete-red.svg">Отклоненые <span id="count-reject">0</span></a></li>
                </ul>

                <a href="#" class="settings"><img src="assets/img/icons/engineering.svg">Настройки</a>
            </div>
            <div class="content"></div>
        </div>
    </div>

    <div class="popup-article animated fadeIn">
        <div class="window">
            <a href="#" class="close"><img src="assets/img/icons/cancel.svg"></a>
            <div class="panel">
                <a href="#" class="post" id="set-publish"><img src="assets/img/icons/check-mark.svg">Опубликовать</a>
                <a href="#" class="post" id="set-moder"><img src="assets/img/icons/clock.svg">На модерацию</a>
                <a href="#" class="reject" id="set-reject"><img src="assets/img/icons/delete-red.svg">Отклонить</a>
                <a href="#" class="reject" id="set-delete"><img src="assets/img/icons/delete-button.svg">Удалить</a>
            </div>
            <article>
                <h1>Анализ запросов стороннего сайта и подбор антидетект и сетевых подключений для работы с ним.</h1>
                <div class="detail">
                    <div class="author">Admin</div>
                    <address>July 07, 2019</address>
                    <div class="link"><a href="#" target="_blank">перейти к статье</a></div>
                </div>
                <p>Исполнитель должен обладать расширенными навыками в антитедекте, анонимности в интернете, безопасных сетевых подключениях.</p>
            </article>
        </div>
    </div>

    <div class="popup-settings animated fadeIn">
        <div class="window">
            <a href="#" class="close-settings"><img src="assets/img/icons/cancel.svg"></a>
            <div class="header"><span><img src="assets/img/icons/engineering.svg">Настройки</span></div>
            <div class="nav">
                <ul>
                    <li class="active"><a href="#"><img src="assets/img/icons/edit.svg"> Управление лимитами</a></li>
                </ul>
            </div>
            <div class="setting-content">
                <div class="input-item">
                    <label>Ограничение на кол-во постов в сутки</label>
                    <div class="input">
                        <input type="text" placeholder="0" id="post-limits">
                    </div>
                </div>

                <div class="input-item">
                    <label>Максимальный размер поста в MB</label>
                    <div class="input">
                        <input type="text" placeholder="0" id="post-limits-size">
                    </div>
                </div>
                <div class="footer"><a href="#" id="save-limits" class="btn-save">Сохранить</a></div>
            </div>
        </div>
    </div>

    <script>
        var tab_active = 0,
            check_ids = [];

        /** Функция удаляет элеемнт массива по значению **/
        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };

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

            $('.popup-article').find('.close').click(function (e) {
                $('.popup-article').css({'display':'none'});
            });

            $('article').click(function () {
                $('.popup-article').css({'display':'block'});
            });

            $('.settings').click(function (e) {
                $('.popup-settings').css({'display':'block'});
            });

            $('.close-settings').click(function (e) {
                $('.popup-settings').css({'display':'none'});
            });

            var app = {
                convertDate: function (time) {
                    time = new Date(time * 1000);

                    var month = 0;
                    switch (time.getMonth()) {
                        case 0: month = 'Янв'; break;
                        case 1: month = 'Фев'; break;
                        case 2: month = 'Мар'; break;
                        case 3: month = 'Апр'; break;
                        case 4: month = 'Май'; break;
                        case 5: month = 'Июн'; break;
                        case 6: month = 'Июл'; break;
                        case 7: month = 'Авг'; break;
                        case 8: month = 'Сен'; break;
                        case 9: month = 'Окт'; break;
                        case 10: month = 'Ноя'; break;
                        case 11: month = 'Дек'; break;
                    }

                    console.log(month);

                    return time.getDate() + ' ' + month + ' ' + time.getFullYear();
                },

                getStats: function () {
                    $.ajax({
                        url: '/content/getStats',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        success: function (data) {
                            data = JSON.parse(data);

                            $('#count-publish').text(data.publish);
                            $('#count-moder').text(data.moder);
                            $('#count-reject').text(data.reject);
                        }
                    });
                },

                getPublish: function () {
                    $.ajax({
                        url: '/content/publish',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        beforeSend: function () {
                            console.log('load publish...');
                            $('#check-all').data('check', false);
                            $('#check-all').removeClass('checked');
                            check_ids = [];

                            $('#check-publish').css({'display':'none'});
                            $('#check-reject').css({'display':'flex'});
                            $('#check-moder').css({'display':'flex'});
                        },
                        success: function (data) {
                            tab_active = 1;
                            $('.content').html('');
                            var data = JSON.parse(data);

                            $(data).each(function (k, v) {
                                var time = data.updated_at;
                                time = app.convertDate(v.updated_at);

                                $('.content').append('<article id="article-post-' + v.id + '" data-check="false" data-tab="1" data-id="' + v.id + '">' +
                                    '<div class="check"></div>' +
                                    '<div class="article-title">' + v.title + '</div>' +
                                    '<div class="detail">' +
                                    '<div class="author">' + v.author + '</div>' +
                                    '<div class="time">' + time + '</div>' +
                                    '</div>' +
                                    '</article>');
                            });
                        }
                    });
                },

                getModer: function () {
                    $.ajax({
                        url: '/content/moder',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        beforeSend: function () {
                            console.log('load moder...');
                            $('#check-all').data('check', false);
                            $('#check-all').removeClass('checked');
                            check_ids = [];

                            $('#check-publish').css({'display':'flex'});
                            $('#check-reject').css({'display':'flex'});
                            $('#check-moder').css({'display':'none'});
                        },
                        success: function (data) {
                            tab_active = 2;
                            $('.content').html('');
                            var data = JSON.parse(data);

                            $(data).each(function (k, v) {
                                var time = data.updated_at;
                                time = app.convertDate(v.updated_at);

                                $('.content').append('<article id="article-post-' + v.id + '" data-check="false" data-tab="2" data-id="' + v.id + '">' +
                                    '<div class="check"></div>' +
                                    '<div class="article-title">' + v.title + '</div>' +
                                    '<div class="detail">' +
                                    '<div class="author">' + v.author + '</div>' +
                                    '<div class="time">' + time + '</div>' +
                                    '</div>' +
                                    '</article>');
                            });
                        }
                    });
                },

                getReject: function () {
                    $.ajax({
                        url: '/content/reject',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        beforeSend: function () {
                            console.log('load publish...');
                            $('#check-all').data('check', false);
                            $('#check-all').removeClass('checked');
                            check_ids = [];

                            $('#check-publish').css({'display':'flex'});
                            $('#check-reject').css({'display':'none'});
                            $('#check-moder').css({'display':'flex'});
                        },
                        success: function (data) {
                            tab_active = 3;
                            $('.content').html('');
                            var data = JSON.parse(data);

                            $(data).each(function (k, v) {
                                var time = data.updated_at;
                                time = app.convertDate(v.updated_at);

                                $('.content').append('<article id="article-post-' + v.id + '" data-check="false" data-tab="3" data-id="' + v.id + '">' +
                                    '<div class="check"></div>' +
                                    '<div class="article-title">' + v.title + '</div>' +
                                    '<div class="detail">' +
                                    '<div class="author">' + v.author + '</div>' +
                                    '<div class="time">' + time + '</div>' +
                                    '</div>' +
                                    '</article>');
                            });
                        }
                    });
                },

                setDelete: function () {
                    var id = $('.popup-article').data('id');

                    $.ajax({
                        url: '/content/delete',
                        type: 'post',
                        data: 'id=' + id,
                        contentType: 'application/x-www-form-urlencoded',
                        success: function (data) {
                            console.log(data);
                            $('.popup-article').css({'display':'none'});
                            $('#article-post-' + id).remove();
                            app.getStats();
                        }
                    });
                },

                getContentById: function (item) {
                    var id = $(item).data('id');

                    $.ajax({
                        url: '/content/getContentById',
                        type: 'post',
                        data: 'id=' + id,
                        beforeSend: function () {
                            console.log('load content by id...');
                        },
                        success: function (data) {
                            var data = JSON.parse(data);
                            var time = data.updated_at;
                            time = app.convertDate(time);

                            switch (data.publish) {
                                case '0':
                                    $('#set-publish').css({'display':'flex'});
                                    $('#set-reject').css({'display':'flex'});
                                    $('#set-moder').css({'display':'none'});
                                    break;
                                case '1':
                                    $('#set-publish').css({'display':'none'});
                                    $('#set-reject').css({'display':'none'});
                                    $('#set-moder').css({'display':'flex'});
                                    break;
                                case '2':
                                    $('#set-publish').css({'display':'flex'});
                                    $('#set-reject').css({'display':'none'});
                                    $('#set-moder').css({'display':'none'});
                                    break;
                            }

                            if (data.error == undefined) {
                                $('.popup-article').attr('data-id', data.id);
                                $('.popup-article').css({'display':'block'});
                                $('.popup-article').find('h1').html(data.title);
                                $('.popup-article').find('.author').html(data.author);
                                $('.popup-article').find('address').html(time);
                                $('.popup-article').find('.link').find('a').attr('href', '@' + data.url);
                                $('.popup-article').find('article').find('p').html(data.text);

                                resizeYoutube();
                            } else {
                                console.log(data.message);
                            }

                        }
                    });
                }
            }

            var setting = {
                saveLimits: function () {
                    var limits = $('#post-limits').val(),
                        limits_size = $('#post-limits-size').val();

                    $.ajax({
                        url: '/setting/setLimit',
                        type: 'post',
                        data: 'limit=' + limits + '&post_size=' + limits_size,
                        contentType: 'application/x-www-form-urlencoded',
                        beforeSend: function () {
                            $('#save-limits').text('Сохраняю..');
                            $('#save-limits').css({'background-color':'#009cff'});
                        },
                        success: function (data) {
                            $('#save-limits').text('Сохранить');
                            $('#save-limits').css({'background-color':'#05c46b'});
                        }
                    });
                },

                loadLimits: function () {
                    $.ajax({
                        url: '/setting/getLimit',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        success: function (data) {
                            var json = JSON.parse(data);
                            $('#post-limits').val(json.limit);
                            $('#post-limits-size').val(json.post_size);
                        }
                    });
                },

            };


            /* Получаем данные категорий [опубликованы / модерация / отклонено] */
            $('#get-publish').click(function (e) {
                $('.nav').find('ul').find('li').find('a').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
                app.getPublish();
            });

            $('#get-moder').click(function (e) {
                $('.nav').find('ul').find('li').find('a').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
                app.getModer();
            });

            $('#get-reject').click(function (e) {
                $('.nav').find('ul').find('li').find('a').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
                app.getReject();
            });

            $('.content').on('click', '.article-title', function (e) {
                e.preventDefault();
                var article = $(this).closest('article');
                app.getContentById(article);
            });

            /* ставим статью как publish */
            $('#set-publish').click(function (e) {
                var id = $(this).closest('.popup-article').data('id');

                $.ajax({
                    url: '/content/setPublish',
                    type: 'post',
                    data: 'id=' + id,
                    beforeSend: function () {
                        console.log('set publish...');

                        $('#set-publish').css({'display':'none'});
                        $('#set-reject').css({'display':'none'});
                        $('#set-moder').css({'display':'flex'});
                    },
                    success: function (data) {
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        app.getStats();
                        console.log('publish set');
                    }
                });
            });

            $('#set-moder').click(function (e) {
                var id = $(this).closest('.popup-article').data('id');

                $.ajax({
                    url: '/content/setModer',
                    type: 'post',
                    data: 'id=' + id,
                    beforeSend: function () {
                        console.log('set moder...');

                        $('#set-publish').css({'display':'flex'});
                        $('#set-reject').css({'display':'flex'});
                        $('#set-moder').css({'display':'none'});
                    },
                    success: function (data) {
                        console.log('moder set');
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        app.getStats();
                    }
                });
            });

            $('#set-reject').click(function (e) {
                var id = $(this).closest('.popup-article').data('id');

                $.ajax({
                    url: '/content/setReject',
                    type: 'post',
                    data: 'id=' + id,
                    beforeSend: function () {
                        console.log('set reject...');

                        $('#set-publish').css({'display':'flex'});
                        $('#set-reject').css({'display':'none'});
                        $('#set-moder').css({'display':'none'});
                    },
                    success: function (data) {
                        console.log('reject set');
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        app.getStats();
                    }
                });
            });

            /*** выделенные статьи ставим пабликом **/
            $('#check-publish').click(function (e) {
                if (check_ids.length < 1) {
                    alert('Выделите нужные статьи');
                    return false;
                }

                var ids = check_ids;

                $.ajax({
                    url: '/content/checkPublish',
                    type: 'post',
                    data: 'ids=' + JSON.stringify(ids),
                    beforeSend: function () {
                        console.log('check publish...');
                    },
                    success: function (data) {
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        console.log('publish check');
                        app.getStats();
                    }
                });
            });

            $('#check-moder').click(function (e) {
                if (check_ids.length < 1) {
                    alert('Выделите нужные статьи');
                    return false;
                }

                var ids = check_ids;

                $.ajax({
                    url: '/content/checkModer',
                    type: 'post',
                    data: 'ids=' + JSON.stringify(ids),
                    beforeSend: function () {
                        console.log('check moder...');
                    },
                    success: function (data) {
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        console.log('moder check');
                        app.getStats();
                    }
                });
            });

            $('#check-reject').click(function (e) {
                if (check_ids.length < 1) {
                    alert('Выделите нужные статьи');
                    return false;
                }

                var ids = check_ids;

                $.ajax({
                    url: '/content/checkReject',
                    type: 'post',
                    data: 'ids=' + JSON.stringify(ids),
                    beforeSend: function () {
                        console.log('check reject...');
                    },
                    success: function (data) {
                        switch (tab_active) {
                            case 1: app.getPublish(); break;
                            case 2: app.getModer(); break;
                            case 3: app.getReject(); break;
                        }

                        console.log('reject check');
                        app.getStats();
                    }
                });
            });

            $('#check-delete').click(function (e) {
                if (check_ids.length < 1) {
                    alert('Выделите нужные статьи');
                    return false;
                }

                var ids = check_ids;

                Swal.fire({
                    title: 'Действительно удалить ' + check_ids.length + ' статьи?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Отменить',
                    confirmButtonText: 'Да, удалить!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '/content/checkDelete',
                            type: 'post',
                            data: 'ids=' + JSON.stringify(ids),
                            beforeSend: function () {
                                console.log('check delete...');
                            },
                            success: function (data) {

                                Swal.fire({
                                    title: 'Удалено!',
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                switch (tab_active) {
                                    case 1: app.getPublish(); break;
                                    case 2: app.getModer(); break;
                                    case 3: app.getReject(); break;
                                }

                                console.log('delete check');
                                app.getStats();
                            }
                        });
                    }
                })
            });

            /**********/

            $('.content').on('click', '.check', function (e) {
                e.preventDefault();
                var check_status = $(this).closest('article').data('check'),
                    parent = $(this).closest('article'),
                    id = parent.data('id');

                if (!check_status) {
                    parent.data('check', true);
                    $(this).addClass('checked');
                    check_ids.push(id);
                } else {
                    parent.data('check', false);
                    $(this).removeClass('checked');
                    check_ids.remove(id);
                }

            });

            /** чекаем все **/
            $('#check-all').click('.check', function (e) {
                e.preventDefault();
                var check_status = $(this).data('check');

                if (!check_status) {
                    $(this).data('check', true);
                    $(this).addClass('checked');

                    var articles = $('article[data-tab="' + tab_active + '"]');

                    articles.each(function (k, v) {
                       check_ids.push($(v).data('id'));
                       $(v).data('check', true);
                       $(v).find('.check').addClass('checked');
                    });

                    console.log(check_ids);

                } else {
                    $(this).data('check', false);
                    $(this).removeClass('checked');

                    var articles = $('article[data-tab="' + tab_active + '"]');
                    check_ids = [];

                    articles.each(function (k, v) {
                        $(v).data('check', false);
                        $(v).find('.check').removeClass('checked');
                    });

                    console.log(check_ids);
                }

            });

            $('#set-delete').click(function (e) {
                app.setDelete(this);
            });

            /** Настройки **/
            $('#save-limits').click(function (e) {
               setting.saveLimits();
            });

            /** Инициализация **/
            setting.loadLimits();
            app.getPublish();

        });
    </script>
    </div>

    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>