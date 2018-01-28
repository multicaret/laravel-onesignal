<?php

namespace Liliom\OneSignal;

use Illuminate\Support\Facades\Facade;

class OneSignalFacade extends Facade {

    protected static function getFacadeAccessor() {
        return 'onesignal';
    }

}