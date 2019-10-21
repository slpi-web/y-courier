<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="js/jquery-1.11.2.min.js"></script>
	<script>
		$(document).ready(function() { // вся магия после загрузки страницы
	$("#ajaxform").submit(function(){ // перехватываем все при событии отправки
		var form = $(this); // запишем форму, чтобы потом не было проблем с this
		var error = false; // предварительно ошибок нет
		form.find('input, textarea').each( function(){ // пробежим по каждому полю в форме
			if ($(this).val() == '') { // если находим пустое
				alert('Заполните поле "'+$(this).attr('placeholder')+'"!'); // говорим заполняй!
				error = true; // ошибка
			}
		});
		if (!error) { // если ошибки нет
			var data = form.serialize(); // подготавливаем данные
			$.ajax({ // инициализируем ajax запрос
			   type: 'POST', // отправляем в POST формате, можно GET
			   url: 'gogogo.php', // путь до обработчика, у нас он лежит в той же папке
			   dataType: 'json', // ответ ждем в json формате
			   data: data, // данные для отправки
		       beforeSend: function(data) { // событие до отправки
		            form.find('input[type="submit"]').attr('disabled', 'disabled'); // например, отключим кнопку, чтобы не жали по 100 раз
		          },
		       success: function(data){ // событие после удачного обращения к серверу и получения ответа
		       		if (data['error']) { // если обработчик вернул ошибку
		       			alert(data['error']); // покажем её текст
		       		} else { // если все прошло ок
		       			alert('Письмо отвравлено! Чекайте почту! =)'); // пишем что все ок
		       		}
		         },
		       error: function (xhr, ajaxOptions, thrownError) { // в случае неудачного завершения запроса к серверу
		            alert(xhr.status); // покажем ответ сервера
		            alert(thrownError); // и текст ошибки
		         },
		       complete: function(data) { // событие после любого исхода
		            form.find('input[type="submit"]').prop('disabled', false); // в любом случае включим кнопку обратно
		         }
		                  
			     });
		}
		return false; // вырубаем стандартную отправку формы
	});
});


	</script>
</head>
<body>



<form method="post" action="" id="ajaxform"> <br />
<input type="text" size="32" maxlength="36" name="name" placeholder="Ваше имя" val=""> <br />
<input type="text" size="32" maxlength="36" name="email" placeholder="Ваш email" val=""> <br />
<input type="text" size="32" maxlength="36" name="subject" placeholder="Тема" val=""> <br />
<textarea cols="25" rows="10" name="message" placeholder="Сообщение.." val=""></textarea> <br />
<input type="submit" value="GO GO GO"/>
</form>
	
</body>
</html>