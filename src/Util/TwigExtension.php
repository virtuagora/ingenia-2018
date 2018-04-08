<?php

namespace App\Util;

use Twig_Extension;
use Twig_SimpleFunction;

class TwigExtension extends Twig_Extension
{
    private $router;
    private $request;
    private $uri;

    public function __construct($router, $request, $uri = null)
    {
        $this->router = $router;
        $this->request = $request;
        $this->uri = $uri;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('asset', [$this, 'asset']),
            new Twig_SimpleFunction('path_for', [$this, 'pathFor']),
            new Twig_SimpleFunction('base_url', [$this, 'baseUrl']),
            new Twig_SimpleFunction('is_current_path', [$this, 'isCurrentPath']),
            new Twig_SimpleFunction('avatar_url', array($this, 'avatarUrlFunction'))
        ];
    }

    /*public function getGlobals()
    {
        return [
            'subject' => $this->request->getAttribute('subject')->toArray(),
        ];
    }*/

    public function asset($name)
    {
        return $this->baseUrl().'/assets/'.$name;
    }

    // TODO analizar si agregar parametro $absolute
    public function pathFor($name, $data = [], $query = [])
    {
        return $this->router->pathFor($name, $data, $query);
    }

    public function baseUrl()
    {
        if (is_string($this->uri)) {
            return $this->uri;
        }
        if (method_exists($this->uri, 'getBaseUrl')) {
            return $this->uri->getBaseUrl();
        }

        return rtrim(str_ireplace('index.php', '', $this->request->getUri()->getBaseUrl()), '/');
    }

    public function isCurrentPath($name)
    {
        return $this->router->pathFor($name) === $this->uri->getPath();
    }

    public function setBaseUrl($baseUrl)
    {
        $this->uri = $baseUrl;
    }

    public function avatarUrlFunction($type, $hash, $size) {
        switch ($type) {
            case 0:
                return 'https://www.gravatar.com/avatar/'.$hash.'?d=identicon&s='.$size;
            case 1:
                return 'http://graph.facebook.com/'.$hash.'/picture?width='.$size;
            default:
                return 'https://www.gravatar.com/avatar/'.$hash.'?d=identicon&s='.$size;
                // return Slim\Slim::getInstance()->request()->getRootUri().'/img/usuario/'.$hash.'/'.$size.'.png';
        }
    }

    public function getName()
    {
        return 'app';
    }
}
