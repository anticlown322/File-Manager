<?php

namespace services;

class TemplateEngine
{
    public function __construct(private string $templatesBaseDir = '')
    {
    }

    /**
     * @param array<string, string> $parameters
     */
    public function render(string $template, array $parameters): string
    {
        $path = $this->getFullPath($template);

        if (! file_exists($path)) {
            return 'Error';
        }

        $content = file_get_contents($path);

        foreach ($parameters as $key => $value) {
            $content = preg_replace(sprintf('/{%s}/', $key), $value, $content);
        }

        return $content;
    }

    private function getFullPath(string $template): string
    {
        return $this->templatesBaseDir . $template;
    }
}