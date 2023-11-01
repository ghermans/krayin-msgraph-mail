<?php

namespace Ghermans\MicrosoftGraphMail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class MsGraphMailClient
{
    protected $baseUrl = 'https://graph.microsoft.com/v1.0/';
    protected $accessToken;

    /**
     * Create a new MsGraphMailClient instance.
     *
     * @param string|null $accessToken
     */
    public function __construct($accessToken = null)
    {
        $this->accessToken = $accessToken ?? $this->getAccessToken();
    }

    /**
     * Retrieve emails from the Microsoft Graph API.
     *
     * @return array|null
     */
    public function getEmails()
    {
        $url = $this->baseUrl . 'me/mailFolders/inbox/messages';

        $response = $this->makeGraphRequest('GET', $url);

        if ($response->successful()) {
            return $response->json();
        } else {
            // Handle the error appropriately in your application
            $this->handleError($response);
            return null;
        }
    }

    /**
     * Make an HTTP request to the Microsoft Graph API.
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    protected function makeGraphRequest($method, $url, $data = [])
    {
        return Http::withToken($this->accessToken)
            ->$method($url, $data);
    }

    /**
     * Get an access token for Microsoft Graph API.
     *
     * @return string|null
     */
    protected function getAccessToken()
    {
        $tokenResponse = $this->requestAccessToken();

        if ($tokenResponse === null) {
            return null;
        }

        return $tokenResponse['access_token'];
    }

    /**
     * Request an access token from the token endpoint.
     *
     * @return array|null
     */
    protected function requestAccessToken()
    {
        $clientId = config('msgraph.client_id');
        $clientSecret = config('msgraph.client_secret');
        $tenantId = config('msgraph.tenant_id');
        $tokenEndpointUrl = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
        $scope = 'https://ps.outlook.com/.default';


        $response = Http::asForm()->post($tokenEndpointUrl, [
            'client_id' => $clientId,
            'scope' => $scope,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['access_token'])) {
                return $data;
            } else {
                // Handle the absence of access_token appropriately in the application
                $this->handleError($response, 'Access token not found in the response.');
                return null;
            }
        } else {
            // Handle the error appropriately in your application and log it
            $this->handleError($response, 'Error while obtaining access token.');
            return null;
        }
    }

    /**
     * Handle errors by logging them and potentially performing additional actions.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @param string $message
     */
    protected function handleError(Response $response, $message = '')
    {
        // Handle and log the error appropriately in your application
        $logMessage = 'HTTP status: ' . $response->status();
        if (!empty($message)) {
            $logMessage .= ' - ' . $message;
        }
        Log::error($logMessage);
    }
}
