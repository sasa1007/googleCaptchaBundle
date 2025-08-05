# Google Captcha Bundle

Simple Symfony bundle for Google reCAPTCHA integration.

## Installation

```bash
composer require sasa1007/google-captcha-bundle
```

**Note:** After installation, you need to manually configure the bundle as described below.

## Configuration


Create configuration file `config/packages/google_captcha.yaml`:

```yaml
google_captcha:
    secret: '%env(GOOGLE_CAPTCHA_SECRET)%'
```

Add to your `.env` file:

```
GOOGLE_CAPTCHA_SECRET=your_google_recaptcha_secret_key
```

**Note:** Replace `your_google_recaptcha_secret_key` with your actual Google reCAPTCHA secret key from [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin).

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

## Uninstallation

To remove the bundle:

1. Delete configuration file:
```bash
rm config/packages/google_captcha.yaml
```

2. Remove from composer:
```bash
composer remove sasa1007/google-captcha-bundle
```

**Note:** The bundle is automatically unregistered and service definitions are removed when you uninstall the package. 