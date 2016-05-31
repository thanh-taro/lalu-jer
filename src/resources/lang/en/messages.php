<?php

return [
    '400.title' => 'Bad Request',
    '400.detail' => 'The request cannot be fulfilled due to bad syntax.',
    '401.title' => 'Unauthorized',
    '401.detail' => 'Authentication is required and has failed or has not yet been provided.',
    '403.title' => 'Forbidden',
    '403.detail' => 'The server understood the request but refuses to authorize it.',
    '404.title' => 'Not Found',
    '404.detail' => 'The requested resource could not be found but may be available again in the future.',
    '405.title' => 'Method Not Allowed',
    '405.detail' => 'A request was made of a resource using a request method not supported by that resource.',
    '406.title' => 'Not Acceptable',
    '406.detail' => 'The requested resource is only capable of generating content not acceptable.',
    '408.title' => 'Request Timeout',
    '408.detail' => 'The server did not receive a complete request message in time.',
    '409.title' => 'Conflict',
    '409.detail' => 'The request could not be processed because of conflict in the request.',
    '410.title' => 'Gone',
    '410.detail' => 'The requested resource is no longer available and will not be available again.',
    '411.title' => 'Length Required',
    '411.detail' => 'The request did not specify the length of its content, which is required by the resource.',
    '412.title' => 'Precondition Failed',
    '412.detail' => 'The server does not meet one of the preconditions that the requester put on the request.',
    '413.title' => 'Payload Too Large',
    '413.detail' => 'The server cannot process the request because the request payload is too large.',
    '414.title' => 'URI Too Long',
    '414.detail' => 'The request-target is longer than the server is willing to interpret.',
    '415.title' => 'Unsupported Media Type',
    '415.detail' => 'The request entity has a media type which the server or resource does not support.',
    '417.title' => 'Expectation Failed',
    '417.detail' => 'The expectation given could not be met by at least one of the inbound servers.',
    '422.title' => 'Unprocessable Entity',
    '422.detail' => 'The request was well-formed but was unable to be followed due to semantic errors.',
    '426.title' => 'Upgrade Required',
    '426.detail' => 'The server cannot process the request using the current protocol.',
    '428.title' => 'Precondition Required',
    '428.detail' => 'The origin server requires the request to be conditional.',
    '429.title' => 'Too Many Requests',
    '429.detail' => 'The user has sent too many requests in a given amount of time.',
    '500.title' => 'Internal Server Error',
    '500.detail' => 'An error has occurred and this resource cannot be displayed.',
    '501.title' => 'Not Implemented',
    '501.detail' => 'The server either does not recognize the request method, or it lacks the ability to fulfil the request.',
    '502.title' => 'Bad Gateway',
    '502.detail' => 'The server was acting as a gateway or proxy and received an invalid response from the upstream server.',
    '503.title' => 'Service Unavailable',
    '503.detail' => 'The server is currently unavailable. It may be overloaded or down for maintenance.',
    '504.title' => 'Gateway Timeout',
    '504.detail' => 'The server was acting as a gateway or proxy and did not receive a timely response from the upstream server.',
    '505.title' => 'HTTP Version Not Supported',
    '505.detail' => 'The server does not support the HTTP protocol version used in the request.',
    'validation_error.title' => 'Validation Error',
    'token_mismatch.title' => 'Token Mismatch',
    'token_mismatch.detail' => 'The given token is mismatch',
    'authorization_error.title' => 'Unauthorized',
    'authorization_error.detail' => 'Authentication is required and has failed or has not yet been provided.',
];
