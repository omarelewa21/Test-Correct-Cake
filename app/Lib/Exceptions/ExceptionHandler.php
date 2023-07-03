<?php

class ExceptionHandler extends ErrorHandler {

    public static function handleException($exception)
    {
        if($exception instanceof MissingActionException && !AuthComponent::user()){
            header('Location: /');
            exit;
        }

        return parent::handleException($exception);
    }
}