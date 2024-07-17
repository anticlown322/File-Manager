<?php

namespace classes;

use services\TemplateEngine;

class View
{

    private object $model;
    private object $controller;
    private object $templateEngine;
    private array $result;

    public function __construct(Model $model, Controller $controller)
    {
        $this->model = $model;
        $this->controller = $controller;
        $this->templateEngine = new TemplateEngine('resources/views/');
    }

    public function getNewPage(): string
    {
        $data = $this->extractData($_POST);
        $res = $this->model->showFiles($data);
        $this->result = $res['result'];
        return $this->getMainForm();
    }

    private function getMainForm(): string
    {
        //layout generation
        $header = $this->templateEngine->render('header.html', ['logout-action' => 'services/logout.php']);
        $footer = $this->templateEngine->render('footer.html', []);

        return $this->generateMainForm($header, $footer);
    }

    public function getRenameForm(): string
    {
        //layout generation
        $header = $this->templateEngine->render('header.html', ['logout-action' => 'services/logout.php']);
        $footer = $this->templateEngine->render('footer.html', []);

        //form generation
        $filename = $_GET['file'];
        return $this->generateRenameForm($header, $filename, $footer);
    }

    private function generateMainForm($header, $footer): string
    {
        //table generation
        $rows = '';

        foreach ($this->result as $item) {
            $preview = '';

            if ($this->model->isImage($item)) {
                $preview = "<img src='$item' width='100' height='100'><br/>";
            } elseif ($this->model->isTextFile($item)) {
                $preview = $this->model->getTextPreview($item) . "<br/>";
            }

            $rows .= $this->templateEngine->render(
                'table-item.html',
                [
                    'item-name' => basename($item),
                    'type-or-size' => is_dir($item) ? 'Folder' : $this->convertFileSize(filesize($item)),
                    'item-date-modification' => str_replace(date('F j, Y'), 'Today,', date('F j, Y H:ia', filemtime($item))),
                    'delete-link' => 'services/delete.php?file=' . urlencode($item),
                    'rename-link' => 'rename.php?file=' . urlencode($item),
                    'item-link' => Model::UPLOAD_DIR . basename($item),
                    'preview' => $preview
                ]);
        }

        $content = $this->templateEngine->render(
            'table.html',
            [
                'table-rows' => $rows
            ]
        );

        return $this->templateEngine->render(
            'layout.html',
            [
                'title' => 'File Storage',
                'styles-layout' => 'resources/styles/layout.css',
                'styles-table' => 'resources/styles/file-manager.css',
                'header' => $header,
                'create-link' => 'services/upload.php',
                'current-dir' => $this->model->current_directory,
                'content' => $content,
                'footer' => $footer
            ]
        );
    }

    public function generateLoginForm(): string
    {
        $errorStr = '';
        if (hasMessage('error'))
            $errorStr = "<div class='notice error'>" . getMessage('error') . "</div>";

        $oldName = old('name');

        $nameErrorStr='';
        if(hasValidationError('name'))
            $nameErrorStr = "<small>" . validationErrorMessage('name') . "</small>";

        $ariaValue = validationErrorAttr('name');

        return $this->templateEngine->render(
            'login.html',
            [
                'theme' => 'dark',
                'title' => 'Welcome to Anticlown File Storage',
                'styles-login' => 'resources/styles/login.css',
                'action-login' => 'services/login.php',
                'error' => $errorStr,
                'name-old-value' => $oldName,
                'name-error' => $nameErrorStr,
                'aria-value' => $ariaValue,
                'registration-page' => 'register.php'
            ]
        );
    }

    public function generateRegistrationForm(): string
    {
        $oldName = old('name');

        $nameErrorStr='';
        if(hasValidationError('name'))
            $nameErrorStr = "<small>" . validationErrorMessage('name') . "</small>";

        $nameAriaValue = validationErrorAttr('name');

        $passwordErrorStr='';
        if(hasValidationError('password'))
            $passwordErrorStr = "<small>" . validationErrorMessage('password') . "</small>";

        $passwordAriaValue = validationErrorAttr('name');

        return $this->templateEngine->render(
            'register.html',
            [
                'theme' => 'dark',
                'title' => 'Registration',
                'styles-login' => 'resources' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . 'login.css',
                'action-register' => 'services' . DIRECTORY_SEPARATOR . 'register.php',
                'old-name-value' => $oldName,
                'name-error' => $nameErrorStr,
                'name-aria-value' => $nameAriaValue,
                'password-error' => $passwordErrorStr,
                'password-aria-value' => $passwordAriaValue,
                'login-page' => 'index.php'
            ]
        );
    }

    private function generateRenameForm($header, $filename, $footer): string
    {
        return $this->templateEngine->render(
            'rename.html',
            [
                'title' => 'File Storage',
                'styles-layout' => 'resources/styles/layout.css',
                'styles-file-manager' =>'resources/styles/file-manager.css',
                'header' =>$header,
                'filename' => basename($filename),
                'footer' => $footer,
                'action-rename' => 'services/rename.php?file=' . urlencode($filename)
            ]
        );
    }

    private function extractData($source): array
    {
        $data['operation'] = '';
        $data['result'] = [];

        if (isset($source['operation'])) {
            $data['operation'] = substr(trim((string)($source['operation'])), 0, 1);
        }

        return $data;
    }

    private function convertFileSize($bytes, $precision = 2): string
    {
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}