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
    public function project_ownership(GeneralSettings $settings)
    {
        try {
            return $settings->project_ownership ?? 'Privacy policy not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    /**
     * Get the unit value for unit reservation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitValueForUnitReservation(GeneralSettings $settings)
    {
        return response()->json([
            'unit_value_for_unit_reservation' => $settings->serious_value_for_unit_reservation,
        ], 200);
    }

}
