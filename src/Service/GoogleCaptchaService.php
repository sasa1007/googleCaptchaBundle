<?php

namespace BeckUp\GoogleCaptchaBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleCaptchaService
{
    public function __construct(
        private ParameterBagInterface $params
    ) {
    }

    public function verify(Request $request): object
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'] ?? '';
        $data = [
            'secret' => $this->params->get('google_captcha.secret'),
            'response' => $request->request->get('recaptcha'),
            'remoteip' => $remoteip
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return json_decode($result);
    }
} 