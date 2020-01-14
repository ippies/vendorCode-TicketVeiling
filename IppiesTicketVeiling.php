<?php

namespace Ippies\VendorCode;
use Exception;

/**
 * Class TicketVeiling
 *
 * Example code for TicketVeiling for the connection
 * with the ippies.nl api
 *
 * @package Ippies\VendorCode
 * @author  Stef [stef@marshmallow.dev]
 */
class IppiesTicketVeiling
{
    /**
     * @param string $client_id
     * Client id to connect to the ippies.nl Api.
     */
    private $client_id = '{{ CLIENT_ID }}';


    /**
     * @param string $client_secret
     * Client secret to connect to the ippies.nl Api.
     */
    private $client_secret = '{{ CLIENT_SECRET }}';


    /**
     * @param object/json $response
     * This will hold the last response accessible
     * with ->getResponse();
     */
    private $response;


    /**
     * connectAccount
     * Connect a Ticketveiling.nl account with an ippies.nl
     * account.
     * 
     * @param string $ticketveiling_customer_id
     * @param string $ippies_nl_email_address
     * @return \Ippies\VendorCode\IppiesTicketVeiling
     * @throws Exception
     */
    public function connectAccount ($ticketveiling_customer_id, $ippies_nl_email_address)
    {
    	try {
    		$response = $this->doSecureApiRequest('/tv/user/connect', [
    			'id' => $ticketveiling_customer_id,
	            'email' => $ippies_nl_email_address,
    		]);
    		return $this;

    	} catch (Exception $e) {
    		$this->errorHandler($e);
    	}
    }


    /**
     * disconnectAccount
     * Disconnect a Ticketveiling.nl account from an
     * ippies.nl account.
     * 
     * @param string $ticketveiling_customer_id
     * @return \Ippies\VendorCode\IppiesTicketVeiling
     * @throws Exception
     */
    public function disconnectAccount ($ticketveiling_customer_id)
    {
    	try {
    		$response = $this->doSecureApiRequest('/tv/user/disconnect', [
    			'id' => $ticketveiling_customer_id,
    		]);
    		return $this;

    	} catch (Exception $e) {
    		$this->errorHandler($e);
    	}
    }


    /**
     * newBid
     * Add a reward to the ippies account of the
     * ticketveiling user after a bid.
     * 
     * @param string $ticketveiling_customer_id
     * @param string $ticketveiling_bid_id
     * @param int $amount_in_euro_cents default|1
     * @return \Ippies\VendorCode\IppiesTicketVeiling
     * @throws Exception
     */
    public function newBid ($ticketveiling_customer_id, $ticketveiling_bid_id, $amount_in_euro_cents = 1)
    {
    	try {
    		$response = $this->doSecureApiRequest('/tv/transaction', [
    			'id' => $ticketveiling_customer_id,
    			'order_id' => $ticketveiling_bid_id,
    			'amount' => $amount_in_euro_cents,
    		]);
    		return $this;

    	} catch (Exception $e) {
    		$this->errorHandler($e);
    	}
    }


    /**
     * auctionWon
     * Add a reward to the ippies account of the
     * ticketveiling user winning an auction.
     * 
     * @param string $ticketveiling_customer_id
     * @param string $ticketveiling_auction_id
     * @param int $auction_price_in_euro_cents default|0
     * @return \Ippies\VendorCode\IppiesTicketVeiling
     * @throws Exception
     */
    public function auctionWon ($ticketveiling_customer_id, $ticketveiling_auction_id, $auction_price_in_euro_cents = 0)
    {
    	try {
    		$response = $this->doSecureApiRequest('/tv/auctionwon', [
    			'id' => $ticketveiling_customer_id,
    			'order_id' => $ticketveiling_auction_id,
    			'order_value' => $auction_price_in_euro_cents,
    		]);
    		return $this;

    	} catch (Exception $e) {
    		$this->errorHandler($e);
    	}
    }


    /**
     * getAccessToken
     * Get the access token needed for secure
     * api calls to the ippies.nl Rest Api.
     * 
     * @return string $access_token
     * @throws Exception
     */
    private function getAccessToken ()
    {
        $response = $this->doApiRequest('/grant', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        if (!$response || !isset($response->access_token)) {
        	throw new Exception("Ippies/Vendor/Ticketveiling: Error getting an access token", 1);	
        }
        return $response->access_token;
    }


    /**
     * doSecureApiRequest
     * Do a secure api request. This will automatically add
     * the access token to the Bearer header.
     * 
     * @param string $endpoint
     * @param array $request_data
     * @return object/json $response
     */
    private function doSecureApiRequest ($endpoint, $request_data)
    {
    	return $this->doApiRequest($endpoint, $request_data, [
    		"Authorization: Bearer " . $this->getAccessToken(),
    	]);
    }


    /**
     * doApiRequest
     * Get the response from the ippies.nl Rest Api.
     * 
     * @param string $endpoint
     * @param array $request_data
     * @param array $extra_headers
     * @return object/json $response
     */
    private function doApiRequest ($endpoint, $request_data, $extra_headers = [])
    {
    	$request_data_json = json_encode($request_data);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://rest.ippies.nl" . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data_json,
            CURLOPT_HTTPHEADER => array_merge([
                "Accept: */*",
                "Cache-Control: no-cache",
                "Content-Length: " . strlen($request_data_json),
                "Content-Type: application/json",
            ], $extra_headers)
        ]);

        $response = curl_exec($curl);
        $response = json_decode($response);
        $this->setResponseInformation($response);
        return $response;
    }


    /**
     * getResponse
     * Get the latest response from the api. This can be
     * used the validate if the request was successfull.
     * 
     * @return object/json $response
     */
    public function getResponse ()
    {
    	return $this->response;
    }


    /**
     * setResponseInformation
     * Set the latest Api response in a variable so it can
     * be accessed with the getResponse() method.
     * 
     * @param object/json $response
     * @return void
     */
    private function setResponseInformation ($response)
    {
    	$this->response = $response;
    }


    /**
     * errorHandler
     * This will handle errors while contacting the ippies.nl
     * Rest Api. By default the method will write to the server
     * error log. If you want to handle errors differently, feel
     * free to adjust this method to your liking.
     * 
     * @param Exception $exception
     * @return void
     */
    private function errorHandler (Exception $exception)
    {
    	error_log($exception->getMessage());
    }
}
