# Google Captcha Bundle

Simple Symfony bundle for Google reCAPTCHA integration.

## Installation

```bash
composer require backend2-plus/google-captcha-bundle
```

## Configuration

Add bundle to `config/bundles.php`:

```php
return [
    // ... other bundles
    BeckUp\GoogleCaptchaBundle\GoogleCaptchaBundle::class => ['all' => true],
];
```

Add configuration to `config/packages/google_captcha.yaml`:

```yaml
google_captcha:
    secret: '%env(GOOGLE_CAPTCHA_SECRET)%'
```

Add to `.env`:

```
GOOGLE_CAPTCHA_SECRET=your_google_recaptcha_secret_key
```

## Usage

```php
use BeckUp\GoogleCaptchaBundle\Service\GoogleCaptchaService;

class YourController extends AbstractController
{
    public function someAction(Request $request, GoogleCaptchaService $captchaService)
    {
        $result = $captchaService->verify($request);
        
        if ($result->success) {
            // reCAPTCHA passed
        } else {
            // reCAPTCHA failed
        }
    }
}
```

## Frontend

Add Google reCAPTCHA script to your template:

```html
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="g-recaptcha" data-sitekey="your_site_key"></div>
``` 