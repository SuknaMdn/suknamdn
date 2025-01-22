<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class AboutSuknaController extends Controller
{
    public function about(GeneralSettings $settings){
        try {
            return $settings->about ?? 'About section not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function term_and_condition(GeneralSettings $settings){
        try {
            return $settings->term_and_condition ?? 'Terms and conditions not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function privacy_policy(GeneralSettings $settings){
        try {
            return $settings->privacy_policy ?? 'Privacy policy not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

}
