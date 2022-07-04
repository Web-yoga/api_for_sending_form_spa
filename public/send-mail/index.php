<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$errors = array();
	$form_data = array();

	if (empty($_POST['message'])) {
		$errors['message'] = 'error';
	}

	$order = $_POST['message'];

	if (!$errors) {
		$to = "onsilk.ru@ya.ru";
		$headers  = "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: System-message\r\n";
		$subject = "Заявка";
		$message = "{$order}";

		$send = mail($to, $subject, $message, $headers);
	}

	if (!empty($errors)) {
		$form_data['success'] = false;
		$form_data['errors']  = $errors;
	} else {
		$form_data['success'] = true;
		$form_data['posted'] = 'Заявка отправленна';
	}

	echo json_encode($form_data);
} else {
	http_response_code(403);
	echo "error 403!";
}
