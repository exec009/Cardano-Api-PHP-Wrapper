<?php
class Cardano
{
    private $host;
    private $url;
    private $disableSSLVerification;
    public $httpCode;
    
    // The option to not use an SSL certificate doesnt work anymore, so you need to tell curl where to find the certificate. 
    const CERT_PATH = "<path>/<to>/state-wallet-mainnet/tls/client/client.pem";

    public function __construct(string $host, int $port, bool $disableSSLVerification = false)
    {
        $this->disableSSLVerification = $disableSSLVerification;
        $this->host = $host;
        $this->port = $port;
      
    }

    //////////////////////////////
    //  Network Status          //
    //////////////////////////////

    public function getInfo() {
        
        return self::jsonDecode($this->get('/api/v1/node-info'), true);

    }
    //////////////////////////////
    //  Wallet Functions Start  //
    //////////////////////////////
    
    // Returns the Wallet identified by the given walletId.
    public function getWallet(string $walletId): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets/'.$walletId), true);
    }

    // Updates the password for the given Wallet.
    public function updateWallet(string $walletId, array $body):  array
    {
        return self::jsonDecode($this->put('/api/v1/wallets/'.$walletId.'password?old='.$oldPassPhrase.'&new='.$newPassPhrase, $body), true);
    }

    // Deletes the given Wallet and all its accounts.
    public function deleteWallet(string $walletId):  array
    {
        return self::jsonDecode($this->delete('/api/v1/wallets/'.$walletId), true);
    }

    // Returns a list of the available wallets.
    public function getWallets(): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets'), true);
    }

    // Creates a new or restores an existing Wallet.
    public function createWallet(array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/wallets/', $body), true);
    }

    // Wallet Functions End //

    //////////////////////////////
    //  Account Functions Start //
    //////////////////////////////

    // Retrieves a specific Account.
    public function getAccount(string $walletId, string $accountId): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets/'.$walletId.'/accounts/'.$accountId), true);
    }

    // Update an Account for the given Wallet.
    public function updateAccount(string $walletId, string $accountId, array $body):  array
    {
        return self::jsonDecode($this->put('/api/v1/wallets/'.$walletId.'/accounts/'.$accountId, $body), true);
    }


    public function deleteAccount(string $walletId, string $accountId):  array
    {
        return self::jsonDecode($this->delete('/api/v1/wallets/'.$walletId.'/accounts/'.$accountId), true);
    }

    // Retrieves the full list of Accounts.
    public function getAccounts(string $walletId): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets/'.$walletId.'/accounts'), true);
    }

    // Creates a new Account for the given Wallet.
    public function createAccount(string $walletId, array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/wallets/'.$walletId.'/accounts', $body), true);
    }
    // Account Functions End //
    

    //////////////////////////////
    // Address Functions Start  //
    //////////////////////////////


    // Returns a list of the addresses.
    public function getAddresses(): array
    {

        return self::jsonDecode($this->get('/api/v1/addresses'), true);

    }

    // Creates a new Address.
    public function createAddress(string $spendingPassword, string $accountIndex, string $walletId): array
    {
        return self::jsonDecode($this->post('/api/v1/addresses?spendingPassword='.$spendingPassword.'?accountIndex='.$accountIndex.'?walletId='.$walletId), true);
    }

    // Returns interesting information about an address, if available and valid.
    public function addressInfo(string $address): bool
    {
        return $this->get('/api/v1/addresses/'.$address) === 'true';
    }
    // Address Functions End //

    /////////////////////////////////
    // Transaction Functions Start //
    /////////////////////////////////

    // Generates a new transaction from the source to one or multiple target addresses.
    public function createNewTransaction(array $source, array $destination, string $spendingPassword): array
    {
        $groupPolicy = 'OptimizeForSecurity';
        return self::jsonDecode($this->post('/api/v1/transactions?source='.$source.'?destination='.$destination.'?groupingPolicy='.$groupPolicy.'?spendingPassword='.$spendingPassword), true);
    }
    
    // Estimate the fees which would originate from the payment.
    public function estimateTransactionFee(array $source, array $destination, string $spendingPassword): array
    {

        return self::jsonDecode($this->post('/api/v1/transactions/fees?source='.$source.'?destination='.$destination.'?spendingPassword='.$spendingPassword), true);
    }
    
    // Returns the transaction history, i.e the list of all the past transactions.
    public function getTransactionHistory(string $walletId = null, string $accountIndex = null, string $address = null, int $page = null, int $per_page = null, string $id = null, string $created_at = null, string $sort_by = null): array
    {
        $params = [];
        
        if($walletId !== null)
        $params['walletId'] = $walletId;
        
        if($accountIndex !== null)
        $params['accountIndex'] = $accountIndex;
        
        if($address !== null)
        $params['address'] = $address;
        
        if($page !== null)
        $params['page'] = $page;

        if($per_page !== null)
        $params['per_page'] = $per_page;
        
        if($id !== null)
        $params['id'] = $id;

        if($created_at !== null)
        $params['created_at'] = $created_at;

        if($sort_by !== null)
        $params['sort_by'] = $sort_by;
        
        return self::jsonDecode($this->get('/api/v1/transactions?'.http_build_query($params)), true);
    }
    // Transaction Functions End //

   //////////////////////////////
   //     Utility Functions    //
   //////////////////////////////

    // JSON decode
    private static function jsonDecode(string $content): array
    {
        $data = json_decode($content, true);
        if(!is_array($data))
        throw new \Exception($content);
        else
        return $data;
    }

    // CURL Get
    private function get(string $endPoint): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->host.':'.$this->port.$endPoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSLCERT, Cardano::CERT_PATH);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 2);
        if($this->disableSSLVerification)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $data = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_exec($ch) === false)
        {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    // CURL Post
    private function post(string $endPoint, $postFields = []): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host.$endPoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_PORT, $this->port);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLCERT, Cardano::CERT_PATH);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $headers = [
            "Cache-Control: no-cache",
            "Content-Type: application/json;charset=utf-8",
            "Postman-Token: b71b609e-e028-d78b-ce51-3d0ec0b5c9fb",
            "accept: application/json;charset=utf-8"        
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if($this->disableSSLVerification)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $data = curl_exec ($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_exec($ch) === false)
        {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close ($ch);
        return $data;
    }

    // CURL Put
    private function put(string $endPoint, array $putFields = []): string
    {
        $ch = curl_init($endPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_SSLCERT, Cardano::CERT_PATH);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($putFields));
        if($this->disableSSLVerification)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $data = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_exec($ch) === false)
        {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close ($ch);
        return $data;
    }

    // CURL Delete
    public function delete(string $endPoint, array $deleteFields): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($putFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLCERT, Cardano::CERT_PATH);
        if($this->disableSSLVerification)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $data = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_exec($ch) === false)
        {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
		return $data;
    }
    // End Utility Functions //
}

?>
