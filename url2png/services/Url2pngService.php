<?php
namespace Craft;

class Url2pngService extends BaseApplicationComponent {

    protected $settings;
    protected $allowedOptions;

    public function __construct()
    {
        $plugin = craft()->plugins->getPlugin('url2png');
        if (! $plugin)
        {
            throw new Exception('Couldn’t find the Url2png plugin!');
        }
        $this->settings = $plugin->getSettings();

        // Set allowed options
        $this->setAllowedOptions();

    }

    public function create($options)
    {

        # Get your apikey from http://url2png.com/plans
        $URL2PNG_APIKEY = $this->settings['apiKey'];
        $URL2PNG_SECRET = $this->settings['apiSecret'];

        // Clean options
        $options = $this->cleanPassedOptions($options, $this->allowedOptions);

        if (! is_array($options) && in_array('url', $options))
        {
            throw \Craft\Exception(Craft::t('Url2png: No url defined'));
        }

        # create the query string based on the options
        foreach ($options as $key => $value)
        {
            $_parts[] = "$key=$value";
        }

        # create a token from the ENTIRE query string
        $query_string = implode("&", $_parts);
        $TOKEN = md5($query_string . $URL2PNG_SECRET);

        return "http://beta.url2png.com/v6/$URL2PNG_APIKEY/$TOKEN/png/?$query_string";

    }

    private function setAllowedOptions()
    {
        $this->allowedOptions = array(
            'url',
            'thumbnail_max_width', // Default 1:1, can be something like 500
            'viewport', // Defaults to 1480x1037
            'fullpage', // Defaults to false
            'unique', // Forces a fresh screenshot by sending a unique value. A timestamp is suggested.
            'user_agent', // Custom user agent
            'accept_languages', // Accept-Language header. Defaults to en-US,en;q=0.8
            'custom_css_url', // Fetches a CSS stylesheet and injects it
            'say_cheese', // Delay screenshot until <div id='url2png-cheese'></div> is available.
            'ttl', // Set the TTL or "time to live" value for a screenshot in seconds. Defaults to 2592000 (30 days)
        );
    }

    private function cleanPassedOptions($options, $allowedOptions)
    {
        $cleanOptions = array();

        foreach ($options as $key => $value)
        {

            // Check if this option is allowed
            if (! in_array($key, $allowedOptions))
            {
                Craft::log(Craft::t('Url2Png: Option ' . $key . ' not allowed'), LogLevel::Error);
                continue;
            }

            // If this is url, urlencode it
            if ($key === 'url') $value = urlencode($value);

            $cleanOptions[$key] = $value;
        }

        return $cleanOptions;
    }

    public function imgAttributes($options)
    {
        $allowedAttributes = array(
            'class',
            'width',
            'height',
            'alt',
        );

        $attributes = $this->cleanPassedOptions($options, $allowedAttributes);

        return $attributes;
    }

}