# Google Services Detector #

A PHP tool to detect various Google services (e.g., Analytics, Ads, Tag Manager, Maps, Fonts, reCAPTCHA, Search Console) used on a given URL. Includes support for both modern and legacy patterns.




## Features

- 🔍 Detects Google Analytics, Google Ads, Google Tag Manager, Google Maps, Google Fonts, reCAPTCHA, and Google Search Console
- 🕰️ Supports legacy patterns for better detection accuracy
- 🌐 Option to use cURL for fetching the URL content



## Installation

Clone the repository:
```bash
git clone https://github.com/wera-as/Google-Service-Detector.git
```



## Usage

### Example Usage

Create a PHP file (e.g., `example.php`) and include the function:

```php
require 'src/detect_google_services.php';

$url = 'https://example.com';
try {
    $detected_services = detect_google_services($url);
    print_r($detected_services);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

### Options

- `use_curl` (bool): Whether to use cURL for fetching the URL content. Defaults to `false`.




## Function Signature

```php
/**
 * Detect Google services used on a given URL.
 *
 * @param string $url The URL to check for Google services.
 * @param bool $use_curl Optional. Whether to use cURL for fetching the URL content. Defaults to false.
 * @return array An associative array indicating which Google services are detected.
 * @throws Exception If unable to fetch the URL content.
 */
function detect_google_services($url, $use_curl = false);
```



## Return Example

The function returns an associative array where the keys are the names of the Google services and the values are booleans indicating whether the service is detected or not.

Example output:

```php
Array
(
    [Google Analytics] => true
    [Google Ads] => false
    [Google Tag Manager] => true
    [Google Maps] => false
    [Google Fonts] => true
    [reCAPTCHA] => false
    [Google Search Console] => true
)
```



## Error Handling

If the URL content cannot be fetched, the function throws an exception:

```php
try {
    $detected_services = detect_google_services($url);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```



## License

This project is licensed under the GNU GENERAL PUBLIC LICENSE  Version 3




## Contributing

1. 🍴 Fork the repository.
2. 🌿 Create a new branch (`git checkout -b feature-branch`).
3. ✨ Make your changes.
4. 💾 Commit your changes (`git commit -am 'Add new feature'`).
5. 📤 Push to the branch (`git push origin feature-branch`).
6. 🔁 Create a new Pull Request.




## Contact

For any questions or suggestions, please open an issue or submit a pull request.

