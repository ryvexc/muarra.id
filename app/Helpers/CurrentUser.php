<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('currentUser')) {
  function currentUser()
  {
    return Auth::guard('web')->user()
      ?? Auth::guard('staff')->user();
  }
}
