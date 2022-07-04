<?php

use App\Exception\InvalidRequestException;
use App\Exception\UpstreamException;
use App\Service\Http;
use App\Service\HttpUpstream;
use App\Service\Validation;

require_once __DIR__.'/../../vendor/autoload.php';

if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
    Http::optionsResponse(['OPTIONS', 'POST']);
}
 
try {  

    $request = Http::getRequest(
        [
			'lastNameOne' => [Validation::class, 'isName'],
			'id' => 'is_int',
			'last_name' => [Validation::class, 'isName'],
			'first_name' => [Validation::class, 'isName'],
			'patronymic_name' => [Validation::class, 'isName'],
			'birth_date' => [Validation::class, 'isPastDate'],
			'contact_date' => [Validation::class, 'isContactDate'],
			'phone' => 'is_string',
			'contact_type' => 'is_string'
        ]
    );

} catch (InvalidRequestException $e) {

    Http::errorResponse('Введены неверные данные');
	
}

if (null === $request['contacts'] && null === $request['no_contacts_letter']) {
    Http::errorResponse('Поля не могут быть пустыми');
}

$upstream_request = [
    'client_ip' => $_SERVER['REMOTE_ADDR'],
    'contacts'  => $request['contacts'],
];
 
try {
   
    $upstream_response = HttpUpstream::execute(
        ['action' => 'submit-form'] + $upstream_request  
    );
     
    Http::response($upstream_response ?: new stdClass());

} catch (UpstreamException $e) {

    Http::errorResponse(
        'В настоящий момент сервис недоступен. Попробуйте отправить данные через некоторое время.'
    );

}