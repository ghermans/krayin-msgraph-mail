
<p align="center">
    <img src="https://bagisto.com/wp-content/uploads/2021/06/bagisto-logo.png" alt="Krayin CRM">
    <h2 align="center">Microsoft Graph Mail Integration</h2>    
</p

This Kraying CRM package provides a example on how to use the Microsoft Graph API for accessing mail-related data.

## Changelog

For detailed information about changes in each version of this package, please refer to the [Changelog](CHANGELOG.md).

## Installation

You can install this package via Composer:

```bash
composer require ghermans/krayin-msgraph-mail
```

After installation, you can publish the package's configuration file and customize it to suit your needs.  
Make sure to set the required environment variables in your Krayin application. Below are the required environment variables for this package:

```php
MS_GRAPH_CLIENT_ID=your_client_id
MS_GRAPH_CLIENT_SECRET=your_client_secret
MS_GRAPH_TENANT_ID=your_tenant_id
```

## Usage
To use this package, you can create an instance of the MsGraphMailClient class, which allows you to interact with the Microsoft Graph API to retrieve mail-related data.

Here's a basic example of how to use the package in your Laravel application:

```php
use Ghermans\MicrosoftGraphMail\MsGraphMailClient;

// Create an instance of the MsGraphMailClient
$msGraphClient = new MsGraphMailClient();

// Retrieve emails using the client
$emails = $msGraphClient->getEmails();

if ($emails === null) {
    // Handle the error appropriately
} else {
    // Process and display the emails
    return view('emails.index', ['emails' => $emails]);
}
```