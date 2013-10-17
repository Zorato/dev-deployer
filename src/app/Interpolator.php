<?php

namespace Deployer;

use Deployer\Destinations\Destination;
use Deployer\Sources\Source;

class Interpolator
{
    protected $source;
    protected $path;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function interpolate($string)
    {
        $replace = array_merge(
            $this->getDefaultTranslations(),
            $this->getDateTranslations($string)
        );

        return strtr((string) $string, $replace);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function getDefaultTranslations()
    {
        $translations = array(
            '%commit%' => $this->source->getCommit(),
            '%name%' => $this->source->getName(),
            '%branch%' => $this->source->getBranch(),
            '%url%' => $this->source->getUrl(),
        );

        if (!empty($this->path)) {
            $translations['%path%'] = $this->path;
        }

        return $translations;
    }

    protected function getDateTranslations($string)
    {
        // get all strings wrapped by %%... unless it's a known built in translation
        preg_match_all(
            '/%(?!commit|name|branch|url|path|\\s)(.*){1,}%/uU',
            $string,
            $dates
        );
        $translations = array();
        foreach ($dates[1] as $date) {
            // pass all matches strings through PHP's date() function
            // and replacing all spaces with hyphens
            $translations["%$date%"] = preg_replace('/\s/', '-', date($date));
        }

        return $translations;
    }
}
