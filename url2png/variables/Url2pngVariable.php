<?php
namespace Craft;

class Url2pngVariable
{

    public function img($settings = array())
    {
        return $this->renderTemplate('_img', array(
            'src' => $this->_run($settings),
            'attributes' => $this->imgAttributes($settings),
        ));
    }

    public function url($settings = array())
    {
        return $this->_run($settings);
    }

    private function _run($settings)
    {
        return craft()->url2png->create($settings);
    }

    private function imgAttributes($settings)
    {
        return craft()->url2png->imgAttributes($settings);
    }

    private function renderTemplate($templateName, $data = array())
    {
        $oldPath = craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'url2png/templates';
        craft()->path->setTemplatesPath($newPath);
        $html = craft()->templates->render($templateName, $data);
        craft()->path->setTemplatesPath($oldPath);

        return $html;
    }


}