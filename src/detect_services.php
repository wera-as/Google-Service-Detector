<?php

function detect_services($url, $use_curl = false)
{
	// Initialize services array with false as default for each service
	$services = [
		'Google Analytics'          => false,
		'Google Ads'                => false,
		'Google Tag Manager'        => false,
		'Google Search Console'     => false,
		'Cache Policy'              => false,
		'Privacy Policy'            => false,
		'Cookie Consent'            => false,
		'SEO'                       => false,
		'Lazy Load'                 => false,
		'Index'						=> false,
		'Server Type'               => null,
	];

	// Fetch the HTML content and headers of the URL
	$response = $use_curl ? fetch_content_with_curl($url, true) : @file_get_contents($url);
	if (!$response || $response['html'] === false) {
		throw new Exception("Unable to fetch the URL content.");
	}
	$html = $response['html'];
	$services['Server Type'] = $response['server'];  // Assign server type

	// Patterns to search for each service
	$patterns = [
        'Google Analytics'		=> ['/gtag\.js|analytics\.js|ga\.js|UA-|google-analytics\.com|gaq\.push/i'],
        'Google Ads'			=> ['/adsbygoogle\.js|googlesyndication\.com|pagead2\.googlesyndication\.com/i'],
        'Google Tag Manager'	=> ['/googletagmanager\.com|GTM-/i'],
        'Google Search Console'	=> ['/google-site-verification|google\.com\/webmasters\//i'],
        'Cache Policy'			=> ['/litespeed_docref|wp-rocket\.me/i'],
        'Privacy Policy'		=> ['/personvern|personvernerklæring|privacy policy|\/personvernerklaering/i'],
        'Cookie Consent'		=> ['/cookie/i'],
        'SEO'					=> ['/AIOSEO|Rank Math|Yoast SEO plugin/i'],
        'Lazy Load'				=> ['/loading="lazy"|swiper-lazy|lazyload/i'],
        'Index'					=> ['/noindex|<meta[^>]*name=["\']robots["\'][^>]*content=["\']noindex["\']/i'],
    ];

	// Loop through patterns to detect services
	foreach ($patterns as $service => $servicePatterns) {
        foreach ($servicePatterns as $pattern) {
            if (preg_match($pattern, $html)) {
                $services[$service] = true;
                break;  // Stop checking other patterns if one is found
            }
        }
    }

	return $services;
}

function fetch_content_with_curl($url, $includeHeaders = false)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
	if ($includeHeaders) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	$response = curl_exec($ch);
	$serverType = null;
	$htmlContent = $response;

	if ($includeHeaders) {
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$htmlContent = substr($response, $header_size);

		// Parse headers to find the Server header
		foreach (explode("\r\n", $header) as $line) {
			if (stripos($line, 'Server:') !== false) {
				$serverType = trim(substr($line, 7));  // Extract server information
				break;
			}
		}
	}

	if (curl_errno($ch)) {
		error_log('cURL error: ' . curl_error($ch));
		$htmlContent = false;
	}
	curl_close($ch);

	return ['html' => $htmlContent, 'server' => $serverType];
}