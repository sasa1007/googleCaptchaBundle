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
        $captchaToken = trim((string) $request->request->get('recaptcha', ''));

        if ('' === $captchaToken) {
            return (object) [
                'success' => false,
                'score' => 0.0,
                'error-codes' => ['missing-input-response'],
            ];
        }

        $data = [
            'secret' => $this->params->get('google_captcha.secret'),
            'response' => $captchaToken,
            'remoteip' => $remoteip,
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5,
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if (false === $result) {
            return (object) [
                'success' => false,
                'score' => 0.0,
                'error-codes' => ['captcha-verification-failed'],
            ];
        }

        $decoded = json_decode($result);
        if (!$decoded instanceof \stdClass) {
            return (object) [
                'success' => false,
                'score' => 0.0,
                'error-codes' => ['invalid-captcha-response'],
            ];
        }

        $decoded->success = isset($decoded->success) && true === $decoded->success;
        $decoded->score = isset($decoded->score) ? (float) $decoded->score : null;
        $decoded->action = isset($decoded->action) ? (string) $decoded->action : null;

        if ($decoded->success && null !== $decoded->action && $request->getPathInfo() !== $decoded->action) {
            $decoded->success = false;
            $decoded->score = 0.0;
            $decoded->{'error-codes'} = ['captcha-action-mismatch'];
        }

        // Keep strong validation while supporting tokens that return success without score.
        if ($decoded->success && null === $decoded->score) {
            $decoded->score = 0.9;
        }

        return $decoded;
    }
} 