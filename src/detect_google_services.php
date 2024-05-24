<?php

/**
 * Detect Google services used on a given URL.
 *
 * @param string $url The URL to check for Google services.
 * @param bool $use_curl Optional. Whether to use cURL for fetching the URL content. Defaults to false.
 * @return array An associative array indicating which Google services are detected.
 * @throws Exception If unable to fetch the URL content.
 */
function detect_google_services($url, $use_curl = false)
{
    // Initialize services array
    $services = [
        'Google Analytics'          =>  false,
        'Google Ads'                =>  false,
        'Google Tag Manager'        =>  false,
        'Google Maps'               =>  false,
        'Google Fonts'              =>  false,
        'reCAPTCHA'                 =>  false,
        'Google Search Console'     =>  false,
    ];

    // Patterns to search for each service, including legacy patterns
    $patterns = [
        'Google Analytics'          =>  ['gtag.js', 'analytics.js', 'ga.js', 'UA-', 'google-analytics.com', 'gaq.push'],
        'Google Ads'                =>  ['adsbygoogle.js', 'googlesyndication.com', 'pagead2.googlesyndication.com'],
        'Google Tag Manager'        =>  ['googletagmanager.com', 'GTM-'],
        'Google Maps'               =>  ['maps.googleapis.com', 'maps.google.com'],
        'Google Fonts'              =>  ['fonts.googleapis.com', 'fonts.gstatic.com'],
        'reCAPTCHA'                 =>  ['www.google.com/recaptcha/', 'recaptcha.net'],
        'Google Search Console'     =>  ['google-site-verification', 'google.com/webmasters/'],
    ];

    /**
     * Fetch HTML content using cURL.
     *
     * @param string $url The URL to fetch.
     * @return string|false The fetched HTML content or false on failure.
     */
    function fetch_content_with_curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
        $html = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
            $html = false;
        }
        curl_close($ch);
        return $html;
    }

    // Fetch the HTML content of the URL
    if ($use_curl) {
        $html = fetch_content_with_curl($url);
    } else {
        $html = file_get_contents($url);
        if ($html === false) {
            $html = fetch_content_with_curl($url);
        }
    }

    // Check if fetching the content was successful
    if ($html === false) {
        throw new Exception("Unable to fetch the URL content.");
    }

    // Loop through patterns to detect services
    foreach ($patterns as $service => $servicePatterns) {
        $services[$service] = (bool)array_filter($servicePatterns, fn ($pattern) => stripos($html, $pattern) !== false);
    }

    return $services;
}
