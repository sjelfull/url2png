<?php
namespace Craft;

class Url2pngPlugin extends BasePlugin
{

	function getName()
	{
		return Craft::t('URL2PNG');
	}

	function getVersion()
	{
		return '0.1';
	}

	function getDeveloper()
	{
		return 'Fred Carlsen';
	}

	function getDeveloperUrl()
	{
		return 'http://sjelfull.no';
	}

    public function getSettingsHtml()
    {
        return craft()->templates->render('url2png/_settings', array(
            'settings' => $this->getSettings()
        ));
    }

    protected function defineSettings()
    {
        return array(
            'apiKey' => array(AttributeType::String, 'label' => 'API key'),
            'apiSecret' => array(AttributeType::String, 'label' => 'API secret'),
        );
    }

}
