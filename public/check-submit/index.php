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
			'status_no' => [Validation::class, 'isRequestId'],
		]
	);

} catch (InvalidRequestException $e) {

	Http::errorResponse('Неверно указан номер квитка');

}

try {

	$upstream_response = HttpUpstream::execute(
		[
			'client_ip' => $_SERVER['REMOTE_ADDR'],
			'action' => 'check-status',
			'SubmitNumber' => $request['status_no'],
		]
	);
	Http::response($upstream_response);

} catch (UpstreamException $e) {

	Http::errorResponse(
		'В настоящий момент сервис недоступен. Попробуйте отправить данные через некоторое время.'
	);
	
}
