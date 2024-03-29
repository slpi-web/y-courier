<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this hosting provider. 
Contact your hosting provider regarding PHP configuration for your site.

PHP file generated by Adobe Muse CC 2015.0.2.310
*/

require_once('form_process.php');

$form = array(
	'subject' => 'Отправка Вызвать курьера | Ваш курьер',
	'heading' => 'Отправка новой формы',
	'success_redirect' => '',
	'resources' => array(
		'checkbox_checked' => 'Отмечено',
		'checkbox_unchecked' => 'Флажок не установлен',
		'submitted_from' => 'Формы, отправленные с веб-сайта: %s',
		'submitted_by' => 'IP-адрес посетителя: %s',
		'too_many_submissions' => 'Недопустимо высокое количество отправок с этого IP-адреса за последнее время',
		'failed_to_send_email' => 'Не удалось отправить сообщение эл. почты',
		'invalid_reCAPTCHA_private_key' => 'Недействительный закрытый ключ reCAPTCHA.',
		'invalid_field_type' => 'Неизвестный тип поля \'%s\'.',
		'invalid_form_config' => 'Недопустимая конфигурация поля \"%s\".',
		'unknown_method' => 'Неизвестный метод запроса сервера'
	),
	'email' => array(
		'from' => '',
		'to' => ''
	),
	'fields' => array(
		'Email' => array(
			'order' => 8,
			'type' => 'email',
			'label' => 'E-mail',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'E-mail\' не может быть пустым.',
				'format' => 'Поле \'E-mail\' содержит недействительное сообщение эл. почты.'
			)
		),
		'custom_U1876' => array(
			'order' => 1,
			'type' => 'string',
			'label' => 'Номер договора',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Номер договора\' не может быть пустым.'
			)
		),
		'custom_U1854' => array(
			'order' => 3,
			'type' => 'string',
			'label' => 'Конт. лицо',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Конт. лицо\' не может быть пустым.'
			)
		),
		'custom_U1905' => array(
			'order' => 2,
			'type' => 'string',
			'label' => 'Компания',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Компания\' не может быть пустым.'
			)
		),
		'custom_U1909' => array(
			'order' => 4,
			'type' => 'string',
			'label' => 'Страна',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Страна\' не может быть пустым.'
			)
		),
		'custom_U1913' => array(
			'order' => 5,
			'type' => 'string',
			'label' => 'Нас. пункт',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Нас. пункт\' не может быть пустым.'
			)
		),
		'custom_U1937' => array(
			'order' => 6,
			'type' => 'string',
			'label' => 'Адрес',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Адрес\' не может быть пустым.'
			)
		),
		'custom_U1941' => array(
			'order' => 7,
			'type' => 'string',
			'label' => 'Телефон',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Телефон\' не может быть пустым.'
			)
		),
		'custom_U1946' => array(
			'order' => 9,
			'type' => 'string',
			'label' => 'Вид отправления',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Вид отправления\' не может быть пустым.'
			)
		),
		'custom_U1950' => array(
			'order' => 10,
			'type' => 'string',
			'label' => 'Число мест',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Число мест\' не может быть пустым.'
			)
		),
		'custom_U1954' => array(
			'order' => 11,
			'type' => 'string',
			'label' => 'Вес, кг',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Вес, кг\' не может быть пустым.'
			)
		),
		'custom_U1958' => array(
			'order' => 12,
			'type' => 'string',
			'label' => 'Габариты, см',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Габариты, см\' не может быть пустым.'
			)
		),
		'custom_U1966' => array(
			'order' => 13,
			'type' => 'string',
			'label' => 'х',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'х\' не может быть пустым.'
			)
		),
		'custom_U1970' => array(
			'order' => 14,
			'type' => 'string',
			'label' => 'х',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'х\' не может быть пустым.'
			)
		),
		'custom_U1975' => array(
			'order' => 15,
			'type' => 'string',
			'label' => 'Объемный вес, кг',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Объемный вес, кг\' не может быть пустым.'
			)
		),
		'custom_U1979' => array(
			'order' => 16,
			'type' => 'string',
			'label' => 'Описание',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Описание\' не может быть пустым.'
			)
		),
		'custom_U1983' => array(
			'order' => 17,
			'type' => 'string',
			'label' => 'Дата забора',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Дата забора\' не может быть пустым.'
			)
		),
		'custom_U1995' => array(
			'order' => 18,
			'type' => 'string',
			'label' => 'Комментарии',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Комментарии\' не может быть пустым.'
			)
		)
	)
);

process_form($form);
?>
