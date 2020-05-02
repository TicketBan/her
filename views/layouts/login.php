<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="assets/css/login.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.6/jquery.jgrowl.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>
</head>
<body>
	<div class='wrapper'>
		<div class='logo'>TELEGRAPH</div>
		<div class='wrapper-auth'>
			<div class='title'>Войти</div>
			<form id="login">
				<input type=text name=login placeholder="Логин">
				<input type=password name=password placeholder="Пароль">
				<input type=submit value="Войти" id='btn-login'>
			</form>
		</div>
	</div>

	<script>
    $('#btn-login').click(function (e) {
        e.preventDefault();

        if ($('.login-panel').find('input[name="login"]').val() == '' || $('.login-panel').find('input[name="password"]').val() == '')
            $.jGrowl("Заполните все поля", { header: 'Ошибка', position: 'center' });
        else {
            var ser = $('#login').serializeArray();
            set = JSON.stringify(ser);
            $.ajax({
                url: '/user/login',
                type: 'post',
                contentType: 'application/x-www-form-urlencoded',
                data: 'data=' + JSON.stringify(ser),
                beforeSend: function () {
                    $('#btn-login').prop("disabled",true);
                    $.jGrowl('Один момент..', { position: 'center' });
                },
                success: function (e) {
                    $('#btn-login').prop("disabled",false);
                    var response = JSON.parse(e);
                    if (response.message == 'ok') {
                        $.jGrowl('Все ок! Входим..', {header: 'Сообщение', position: 'center'});
                        console.log('Авторизация пройдена. Ответ: ' + e);
                        window.location.href = '/panel';
                    } else
                        $.jGrowl(response.error, { header: 'Ошибка', position: 'center' });
                }
            });
        }
    });
</script>
</body>	
</html>