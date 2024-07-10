<?php

namespace classes;

class Controller
{
    public const STATE_INITIAL = 1;
    public const STATE_POSTED = 2;
    public const STATE_ERROR = 4;

    private $model;
    private $state;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->state = $this->detectState();
        $this->changeModelState();
    }

    public function detectState(): int
    {
        if (isset($_POST['operation']))
            return self::STATE_POSTED;
        else
            return self::STATE_INITIAL;
    }

    public function changeModelState(): void
    {
        switch ($this->state) {
            case self::STATE_INITIAL:
                $this->model->setAction(Model::ACTION_SHOW_FORM);
                break;
            case self::STATE_POSTED:
                $this->model->setAction(Model::ACTION_PERFORM_OPERATION);
                break;
        }
    }

    public function getState(): int
    {
        return $this->state;
    }
}