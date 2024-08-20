<?php

namespace App\Http\Controllers;

use App\Events\AlertTriggered;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function triggerAlert()
    {
        event(new AlertTriggered());
        return 'Alert triggered!';
    }
}
