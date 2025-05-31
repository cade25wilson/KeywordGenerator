<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\OAuth2;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtain access token using the Service Account
        $accessToken = $this->getServiceAccountToken();

        // Use the token to make an authenticated request to the Google Cloud API
        // Example: call the Google Vision API or another service

        return response()->json([
            'access_token' => $accessToken
        ]);
    }

    /**
     * Get the access token using the service account.
     */
    private function getServiceAccountToken()
    {
        // Path to your service account JSON key file
        $serviceAccountJsonPath = env('GOOGLE_APPLICATION_CREDENTIALS');

        // Specify the appropriate scopes
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

        // Load service account credentials with scopes
        $credentials = new ServiceAccountCredentials(
            $scopes,
            $serviceAccountJsonPath
        );

        // Fetch the token (this will refresh automatically if needed)
        $accessToken = $credentials->fetchAuthToken();

        // Return the access token; ensure that your JSON file is valid and contains required fields.
        return $accessToken;
    }
    
    // You can implement other CRUD methods as needed.
}
