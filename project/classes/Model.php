<?php

namespace classes;

class Model
{
    public const int ACTION_SHOW_FORM = 1;
    public const int ACTION_PERFORM_OPERATION = 2;
    public const string UPLOAD_DIR = 'files' . DIRECTORY_SEPARATOR;
    private $action;
    public string $current_directory = '';

    public function isImage($filename): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $imageExtensions);
    }

    public function isTextFile($filename): bool
    {
        $textExtensions = ['txt', 'doc', 'docx', 'pdf'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $textExtensions);
    }

    public function getTextPreview($filename): string
    {
        $content = file_get_contents($filename);
        $lines = explode("\n", $content);
        $count = count($lines);
        $lenToShow = min($count, 2);
        return implode("<br>", array_slice($lines, 0, $lenToShow));
    }

    public function setAction(int $action): void
    {
        $this->action = $action;
    }

    public function showFiles(array $data): array
    {
        $this->current_directory = Model::UPLOAD_DIR;

        $results = glob(str_replace(['[', ']', "\f[", "\f]"], ["\f[", "\f]", '[[]', '[]]'], ($this->current_directory ? $this->current_directory : $this->initial_directory)) . '*');

        // If true, directories will appear first in the populated file list
        $directory_first = true;
        if ($directory_first) {
            usort(
                $results,
                function ($a, $b): int {
                    $a_is_dir = is_dir($a);
                    $b_is_dir = is_dir($b);

                    if ($a_is_dir === $b_is_dir) {
                        return strnatcasecmp($a, $b);
                    } else if ($a_is_dir && !$b_is_dir) {
                        return -1;
                    } else if (!$a_is_dir && $b_is_dir) {
                        return 1;
                    }
                });
        }

        return ['operation' => '', 'result' => $results];
    }
}
