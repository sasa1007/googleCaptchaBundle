# Google Captcha Bundle Recipe

This recipe installs and configures the Google Captcha Bundle.

## What it does

- Registers the bundle in `config/bundles.php`
- Creates `config/packages/google_captcha.yaml` with default configuration
- Creates `config/services.yaml` with service definitions
- Adds `GOOGLE_CAPTCHA_SECRET` environment variable to `.env`

## Manual steps

After installation, you need to:

1. Replace `your_google_recaptcha_secret_key` in `.env` with your actual Google reCAPTCHA secret key
2. Add the frontend reCAPTCHA widget to your templates

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