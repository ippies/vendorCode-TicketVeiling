![alt text](https://www.ippies.nl/images/ippies-logo-header.png "ippies.nl")

# Ticketveiling & ippies.nl
This class can be used for easy integration to the ippies.nl Rest Api. All current endpoints available to Ticketveiling are available in this class. Please see the usage and information below. This update is very important because the current API that is based on SOAP is going to be phased out in Q1 2020 because we are unable to keep the old API save and stable.

- [Usage](#usage) 
- [Available methods](#available_methodes) 
    - [connectAccount](#method_connectAccount) 
    - [disconnectAccount](#method_disconnectAccount)
    - [newBid](#method_newBid)
    - [auctionWon](#method_auctionWon)
- [Ticketveiling Integration](#ticketveiling_integration) 
- [PHP versions](#php_versions) 

<a id="usage"></a>
## Usage
First of all, you need to require `IppiesTicketVeiling.php` so the code is available to us. You are free to change the file name as required.
```php 
$ticketveiling_class = 'src/Ippies/VendorCode/IppiesTicketVeiling.php';
require_once $ticketveiling_class;
```

Next you need to add your api keys to the `IppiesTicketVeiling.php` class. Open the file and add it in the private variables.
```php
<?php

...

class IppiesTicketVeiling
{
    private $client_id = '{{ CLIENT_ID }}';
    private $client_secret = '{{ CLIENT_SECRET }}';

    ...

```

<a id="available_methodes"></a>
#### Available methodes
1. [connectAccount](#method_connectAccount)
2. [disconnectAccount](#method_disconnectAccount)
3. [newBid](#method_newBid)
4. [auctionWon](#method_auctionWon)

<a id="method_connectAccount"></a>
`connectAccount`:<br/> 
Connect a Ticketveiling account to an ippies account:
```php 
$ticketveiling_customer_id = 645757;
$ippies_nl_email_address = 'stef@marshmallow.dev';
(new \Ippies\VendorCode\IppiesTicketVeiling)->connectAccount($ticketveiling_customer_id, $ippies_nl_email_address);
```

<a id="method_disconnectAccount"></a>
`disconnectAccount`:<br/> 
Disconnect a Ticketveiling account from an ippies account:
```php 
$ticketveiling_customer_id = 645757;
(new \Ippies\VendorCode\IppiesTicketVeiling)->disconnectAccount($ticketveiling_customer_id);
```

<a id="method_newBid"></a>
`newBid`:<br/> 
Give the user a reward for bidding on Ticketveiling.nl:
```php 
$ticketveiling_customer_id = 645757;
$ticketveiling_bid_id = 'BID_NUMBER_###'; // The unique bid id from Ticketveiling.nl
$amount_in_euro_cents = 1; // Not required, this will be 1 by default.
(new \Ippies\VendorCode\IppiesTicketVeiling)->newBid(
    $ticketveiling_customer_id, 
    $ticketveiling_bid_id,
    $amount_in_euro_cents
);
```

<a id="method_auctionWon"></a>
`auctionWon`:<br/> 
Give the user a reward for winning an auction on Ticketveiling.nl:
```php 
$ticketveiling_customer_id = 645757;
$ticketveiling_auction_id = 'AUCTION_NUMBER_###'; // The unique auction id from Ticketveiling.nl
$auction_price_in_euro_cents = 0; // Not required, this will be 0 by default.
(new \Ippies\VendorCode\IppiesTicketVeiling)->auctionWon(
    $ticketveiling_customer_id, 
    $ticketveiling_auction_id,
    $auction_price_in_euro_cents
);
```

<a id="ticketveiling_integration"></a>
## Ticketveiling integration
Based on code we've have seen in the past we would like to provide a simple snippet to get Ticketveiling started with the new integration with the ippies.nl Rest Api.

<strong>MijnVeilingPage.php</strong>
```php
<?php

$ticketveiling_class = 'src/Ippies/VendorCode/IppiesTicketVeiling.php';
require_once $ticketveiling_class;

//...

public function connectAccount ($TVId, $emailI) {
    (new \Ippies\VendorCode\IppiesTicketVeiling)->connectAccount($TVId, $emailI);
}

//...

public function disconnectAccount ($TVId) {
    (new \Ippies\VendorCode\IppiesTicketVeiling)->disconnectAccount($TVId);
}

```

<a id="php_versions"></a>
## PHP versions
This class has been tested in the PHP versions listed below. If you are using a different PHP version and want us to test it for you, please let us know at <a href="mailto:developer@ippies.nl">developer@ippies.nl</a> and provide the PHP version that you need tested.

- [x] PHP 5.4.45
- [x] PHP 5.6.40
- [x] PHP 7.0.33
- [x] PHP 7.1.26
- [x] PHP 7.2.14
- [x] PHP 7.3.1

___

`Build by Marshmallow`<br/><br/>
<a href="https://marshmallow.dev" target="_blank">
    <img src="https://cdn.marshmallow-office.com/mm/media/mrmallow.png" width="100"/>
</a>
