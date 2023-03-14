<?php

namespace Linkedout\App\services;

use Jenssegers\Blade\Blade;

class TemplateService
{
    protected Blade $blade;
    protected string $viewsPath;
    protected string $cachePath;

    function __construct()
    {
        $this->viewsPath = realpath(__DIR__ . '/../../views');
        $this->cachePath = realpath(__DIR__ . '/../../cache');

        $this->blade = new Blade($this->viewsPath, $this->cachePath);
    }

    public function getBlade(): Blade
    {
        return $this->blade;
    }

    public function addDirectives(): void
    {
        $this->blade->directive('pagestyle', function ($expression) {
            return "<?php echo '<link rel=\"stylesheet\" href=\"/resources/pages/' . {$expression} . '.css\">'; ?>";
        });
    }
}
