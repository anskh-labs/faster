<?php

declare(strict_types=1);

use Faster\Db\Database;
use Faster\Component\Escaper\Escaper;
use Faster\Helper\Url;
use Faster\Helper\Config;
use Faster\Helper\Container;
use Faster\Helper\Db;
use Faster\Helper\Router;
use Faster\Helper\Service;
use Faster\Helper\View;
use Faster\Html\Form;
use Faster\Http\Auth\UserPrincipalInterface;
use Faster\Http\Session\SessionInterface;
use Faster\Model\FormModel;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Set of function helper 
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 */

if (!function_exists('config')) {
    /**
     * config
     *
     * @param  mixed $offset
     * @param  mixed $defaultValue
     * @return mixed
     */
    function config($offset, $defaultValue = null)
    {
        return Config::get($offset, $defaultValue);
    }
}

if (!function_exists('make')) {
    /**
     * make
     *
     * @param  string $id
     * @param  ?array $params
     * @param  bool $shared
     * @return mixed
     */
    function make(string $id, ?array $params = null, bool $shared = false)
    {
        return Container::get($id, $params, $shared);
    }
}
if (!function_exists('site_url')) {
    /**
     * site_url
     *
     * @param  string $path
     * @return string
     */
    function site_url(string $path = ''): string
    {
        return Url::getSiteUrl($path);
    }
}
if (!function_exists('base_url')) {
    /**
     * base_url
     *
     * @param  string $path
     * @return string
     */
    function base_url(string $path = ''): string
    {
        return Url::getHostUrl($path);
    }
}
if (!function_exists('base_path')) {
    /**
     * base_path
     *
     * @param  string $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return Url::getBasePath($path);
    }
}
if (!function_exists('current_url')) {
    /**
     * current_url
     *
     * @return string
     */
    function current_url(): string
    {
        return Url::getCurrentUrl();
    }
}
if (!function_exists('current_path')) {
    /**
     * current_path
     *
     * @param  string $query
     * @return string
     */
    function current_path(string $query = ''): string
    {
        return Url::getCurrentPath($query);
    }
}
if (!function_exists('route')) {
    /**
     * route
     *
     * @param  string $name
     * @param  string $param
     * @return string
     */
    function route(string $name, string $param = ''): string
    {
        if (Router::exists($name)) {
            $route = Router::get($name);
            $url = $route[1];
            if ($pos = strpos($url, '[')) {
                $url = substr($url, 0, $pos);
            }
            if ($pos = strpos($url, '{')) {
                $url = substr($url, 0, $pos);
            }

            return Url::getBasePath($url . $param);
        } else {
            throw new \Exception("Route '$name' is not exist.");
        }
    }
}

if (!function_exists('is_route')) {
    /**
     * is_route
     *
     * @param  array|string $name
     * @return bool
     */
    function is_route($name): bool
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                if (is_route($n))
                    return true;
            }
            return false;
        } elseif (is_string($name)) {
            if (Router::exists($name)) {
                $route = Router::get($name);
                $url = $route[1];
                if ($pos = strpos($url, '[')) {
                    $url = substr($url, 0, $pos);
                }
                if ($pos = strpos($url, '{')) {
                    $url = substr($url, 0, $pos);
                }
                $rpath = Url::getBasePath($url);
                $cpath = Url::getCurrentPath();
                if ($url === $route[1])
                    return $rpath === $cpath;
                else
                    return str_starts_with($cpath, $rpath);
            }
            return false;
        } else {
            return false;
        }
    }
}

if (!function_exists('attr_to_string')) {
    /**
     * attr_to_string
     *
     * @param  array|string $attributes
     * @return string
     */
    function attr_to_string($attributes): string
    {
        if (empty($attributes)) {
            return '';
        }
        if (is_array($attributes)) {
            $atts = '';
            foreach ($attributes as $key => $val) {

                if (is_object($val)) {
                    $val = (array) $val;
                }
                if (is_array($val)) {
                    $val = trim(attr_to_string($val));
                }
                if (is_numeric($key)) {
                    $key = '';
                } else {
                    $key .= '=';
                    $val = "\"$val\"";
                }
                $atts = empty($atts) ? ' ' . $key . $val : $atts . ' ' . $key  . $val;
            }

            return $atts;
        }

        if (is_string($attributes)) {
            return ' ' . $attributes;
        }

        return '';
    }
}
if (!function_exists('session')) {
    /**
     * session
     *
     * @param  string $sessionAttribute
     * @return SessionInterface
     */
    function session(string $sessionAttribute = '__session'): SessionInterface
    {
        return Service::session($sessionAttribute);
    }
}
if (!function_exists('db')) {
    /**
     * db
     *
     * @param  ?string $connection
     * @return Database
     */
    function db(?string $connection = null): Database
    {
        $connection = $connection ?? Db::defaultConnection();
        return Db::get($connection);
    }
}
if (!function_exists('auth')) {
    /**
     * auth
     *
     * @param  string $userAttribute
     * @return UserPrincipalInterface
     */
    function auth(string $userAttribute = '__user'): UserPrincipalInterface
    {
        return Service::user($userAttribute);
    }
}
if (!function_exists('esc')) {

    /**
     * esc
     *
     * @param  array|string $data
     * @param  string $context
     * @param  ?string $encoding
     * @return array|string
     */
    function esc($data, string $context = 'html', ?string $encoding = null)
    {
        $encoding = $encoding ?? 'utf-8';
        if (is_array($data)) {
            foreach ($data as &$value) {
                $value = esc($value, $context);
            }
        }

        if (is_string($data)) {
            $context = strtolower($context);

            // Provide a way to NOT escape data since
            // this could be called automatically by
            // the View library.
            if ($context === 'raw') {
                return $data;
            }

            if (!in_array($context, ['html', 'js', 'css', 'url', 'attr'], true)) {
                throw new InvalidArgumentException('Invalid escape context provided.');
            }

            $method = $context === 'attr' ? 'escapeHtmlAttr' : 'escape' . ucfirst($context);

            static $escaper;
            if (!$escaper) {
                $escaper = new Escaper($encoding);
            }

            if ($encoding && $escaper->getEncoding() !== $encoding) {
                $escaper = new Escaper($encoding);
            }

            $data = $escaper->{$method}($data);
        }

        return $data;
    }
}
if (!function_exists('render')) {
    /**
     * render
     *
     * @param  string $view
     * @param  array $params
     * @param  ?ResponseInterface $response
     * @return ResponseInterface
     */
    function render(string $view, array $params, ?ResponseInterface $response = null): ResponseInterface
    {
        $response = $response ?? make(Response::class, null, true);
        $response->getBody()->write(View::renderer()->render($view, $params));

        return $response;
    }
}
if (!function_exists('render_json')) {
    /**
     * render_json
     *
     * @param  mixed $data
     * @return ResponseInterface
     */
    function render_json($data): ResponseInterface
    {
        return make(JsonResponse::class, [$data]);
    }
}
if (!function_exists('redirect_to')) {
    /**
     * redirect_to
     *
     * @param  mixed $name
     * @param  mixed $param
     * @return ResponseInterface
     */
    function redirect_to(string $name, string $param = ''): ResponseInterface
    {
        return redirect_uri(route($name, $param));
    }
}
if (!function_exists('redirect_uri')) {
    /**
     * redirect_uri
     *
     * @param  mixed $uri
     * @return ResponseInterface
     */
    function redirect_uri(string $uri, int $status = 302): ResponseInterface
    {
        //$headers['location'] = [(string) $uri];
        //return make(Response::class, [$status, $headers]);
        return make(RedirectResponse::class, [$uri, $status]);
    }
}
if (!function_exists('form')) {

    /**
     * create_form
     *
     * @param  FormModel $model
     * @return Form
     */
    function form(FormModel $model): Form
    {
        return new Form($model);
    }
}
