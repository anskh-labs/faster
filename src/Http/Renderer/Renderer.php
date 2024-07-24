<?php

declare(strict_types=1);

namespace Faster\Http\Renderer;

/**
 * Renderer
 * -----------
 * Renderer
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Renderer
 */
class Renderer implements RendererInterface
{
    private array $params = [];
    private string $viewPath;
    private string $fileExtension;

    /**
     * __construct
     *
     * @param  string $viewPath
     * @param  string $fileExtension
     * @return void
     */
    public function __construct(string $viewPath, string $fileExtension = '.phtml')
    {
        $this->viewPath = $viewPath;
        $this->fileExtension = $fileExtension;
    }
    /**
     * @inheritdoc
     */
    public function render(string $view, array $params = []): string
    {
        if (!empty($params)) {
            $this->params = array_merge($this->params, $params);
        }
        extract($this->params, EXTR_SKIP);
        $filename = $this->viewPath . '/' . $view . $this->fileExtension;
        ob_start();
        if (file_exists($filename))
            require $filename;
        else
            echo "File '$filename' doesn't exists.";
        return ob_get_clean();
    }
    /**
     * @inheritdoc
     */
    public function getParam(?string $key = null, $defaultValue = null)
    {
        if ($key) {
            return $this->params[$key] ?? $defaultValue;
        }

        return $this->params;
    }
    /**
     * @inheritdoc
     */
    public function setParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }
}
