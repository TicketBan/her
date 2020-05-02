<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.6/jquery.jgrowl.min.css">

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>
</head>
<body>
	<div class='wrapper'>
		<div class='logo'>TELEGRAPH</div>
		<div class='wrapper-auth'>
			<div class='title'>Регистрация</div>
			<form id="reg">
				<input type=text name=login placeholder="Логин">
				<input type=password name=password placeholder="Пароль">
				<input type=submit value="Регистрация" id='btn-registration'>
			</form>
		</div>
	</div>

	<script>
    $('#btn-registration').click(function (e) {
        e.preventDefault();

        if ($('.login-panel').find('input[name="login"]').val() == '' || $('.login-panel').find('input[name="password"]').val() == '')
            $.jGrowl("Заполните все поля", { header: 'Ошибка', position: 'center' });
        else {
            var ser = $('#reg').serializeArray();
            set = JSON.stringify(ser);
            $.ajax({
                url: '/user/new',
                type: 'post',
                contentType: 'application/x-www-form-urlencoded',
                data: 'data=' + JSON.stringify(ser),
                beforeSend: function () {
                    $('#btn-registration').prop("disabled",true);
                    $.jGrowl('Один момент..', { position: 'center' });
                },
                success: function (e) {
                    $('#btn-registration').prop("disabled", false);
                    var response = JSON.parse(e);
                    if (response.message == 'ok') {
                        console.log('Регистрация успешно завершена. Ответ: ' + e);
                        $.jGrowl('Регистрация завершена!', {header: 'Сообщение', position: 'center'});
                        setTimeout(function () {
                            window.location.href = '/panel';
                        }, 2000);
                    } else
                        $.jGrowl(response.error, {header: 'Ошибка', position: 'center'});
                }
            });
        }
    });
</script>
</body>	
</html>