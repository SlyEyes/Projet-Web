<?php

namespace Linkedout\App\services;

use Jenssegers\Blade\Blade;

/**
 * The template service is responsible for loading the template engine and adding custom directives
 * @package Linkedout\App\services
 */
class TemplateService
{
    protected Blade $blade;
    protected string $viewsPath;
    protected string $cachePath;

    function __construct()
    {
        $this->viewsPath = realpath(__DIR__ . '/../../views');
        $this->cachePath = realpath(__DIR__ . '/../../cache');

        // Check if the cache directory exists, if not, create it
        if (!$this->viewsPath || !is_dir($this->cachePath)) {
            mkdir(__DIR__ . '/../../cache');
        }

        $this->blade = new Blade($this->viewsPath, $this->cachePath);
    }

    /**
     * The getter for the Blade template engine instance
     * @return Blade
     */
    public function getBlade(): Blade
    {
        return $this->blade;
    }

    /**
     * Add custom directives to the template engine
     * @return void
     */
    public function addDirectives(): void
    {
        $this->blade->directive('pagestyle', function ($expression) {
            return "<?php echo '<link rel=\"stylesheet\" href=\"/resources/pages/' . {$expression} . '.css\">'; ?>";
        });

        $this->blade->directive('pagescript', function ($expression) {
            return "<?php echo '<script src=\"/resources/pages/' . {$expression} . '.js\" defer></script>'; ?>";
        });

        $this->blade->directive('componentscript', function ($expression) {
            return "<?php echo '<script src=\"/resources/components/' . {$expression} . '.js\" defer></script>'; ?>";
        });
    }
}
