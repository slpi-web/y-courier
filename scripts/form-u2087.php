<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this hosting provider. 
Contact your hosting provider regarding PHP configuration for your site.

PHP file generated by Adobe Muse CC 2015.0.2.310
*/

require_once('form_process.php');

$form = array(
	'subject' => 'Отправка Тарифный калькулятор | Ваш курьер',
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
		'custom_U2135' => array(
			'order' => 1,
			'type' => 'string',
			'label' => 'Страна',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Страна\' не может быть пустым.'
			)
		),
		'custom_U2143' => array(
			'order' => 2,
			'type' => 'string',
			'label' => 'Населенный пункт',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Населенный пункт\' не может быть пустым.'
			)
		),
		'custom_U2155' => array(
			'order' => 3,
			'type' => 'string',
			'label' => 'Страна',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Страна\' не может быть пустым.'
			)
		),
		'custom_U2096' => array(
			'order' => 4,
			'type' => 'string',
			'label' => 'Населенный пункт',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Населенный пункт\' не может быть пустым.'
			)
		),
		'custom_U2147' => array(
			'order' => 7,
			'type' => 'string',
			'label' => 'Габариты, см',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Габариты, см\' не может быть пустым.'
			)
		),
		'custom_U2105' => array(
			'order' => 8,
			'type' => 'string',
			'label' => 'х',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'х\' не может быть пустым.'
			)
		),
		'custom_U2092' => array(
			'order' => 9,
			'type' => 'string',
			'label' => 'х',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'х\' не может быть пустым.'
			)
		),
		'custom_U2131' => array(
			'order' => 6,
			'type' => 'string',
			'label' => 'Вес, кг',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Вес, кг\' не может быть пустым.'
			)
		),
		'custom_U2117' => array(
			'order' => 5,
			'type' => 'string',
			'label' => 'Вид груза',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Вид груза\' не может быть пустым.'
			)
		),
		'custom_U2101' => array(
			'order' => 10,
			'type' => 'string',
			'label' => 'Объёмный вес, кг',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Объёмный вес, кг\' не может быть пустым.'
			)
		)
	)
);

process_form($form);
?>
