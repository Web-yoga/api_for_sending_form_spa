<?php

use App\Exception\InvalidRequestException;
use App\Exception\UpstreamException;
use App\Service\Http;
use App\Service\HttpUpstream;
use App\Service\Validation;

require_once __DIR__ . '/../../vendor/autoload.php';

if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
	Http::optionsResponse(['OPTIONS', 'POST']);
}

try {

	$request = Http::getRequest(

		[
			'lastNameOne' => [Validation::class, 'isName'],
			'firstNameOne' => [Validation::class, 'isName'],
			'birthdayOne' => [Validation::class, 'isPastDate'],
			'oms' => [Validation::class, 'isOms'],
		]
	);

} catch (InvalidRequestException $e) {

	Http::errorResponse('Данные указаны неверно');

}

try {

	$upstream_response = HttpUpstream::execute(
		[
			'action' => 'check_patient',
			'client_ip' => $_SERVER['REMOTE_ADDR'],
			'lastNameOne' => trim($request['lastNameOne']),
			'firstNameOne' => trim($request['firstNameOne']),
			'oms' => trim($request['oms']),
			'birthdayOne' => $request['birthdayOne'],

		]
	);

	Http::response($upstream_response);

} catch (UpstreamException $e) {

	Http::errorResponse(
		'В настоящий момент сервис недоступен. Попробуйте отправить данные через некоторое время.'
	);

}
