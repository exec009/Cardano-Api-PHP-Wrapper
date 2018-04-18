<?php
class Cardano
{
    private $host;
    private $url;
    private $disableSSLVerification;
    public $httpCode;
    public function __construct(string $host, int $port, bool $disableSSLVerification = false)
    {
        $this->disableSSLVerification = $disableSSLVerification;
        $this->host = $host;
        $this->port = $port;
    }

    // Test Functions Start //
    public function testReset(): array
    {
        return self::json_decode($this->post('/api/v1/test/reset'), true);
    }
    public function testState(): string
    {
        return $this->get('/api/v1/test/state');
    }
    // Test Functions End //

    // Wallet Functions Start //
    public function getWallet(string $walletId): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets/'.$walletId), true);
    }
    public function updateWallet(string $walletId, array $body):  array
    {
        return self::jsonDecode($this->put('/api/v1/wallets/'.$walletId, $body), true);
    }
    public function deleteWallet(string $walletId):  array
    {
        return self::jsonDecode($this->delete('/api/v1/wallets/'.$walletId), true);
    }
    public function getWallets(): array
    {
        return self::jsonDecode($this->get('/api/v1/wallets'), true);
    }
    public function createWallet(array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/wallets/new', $body), true);
    }
    public function importWalletKeys(string $passPhrase, string $key): array
    {
        return self::jsonDecode($this->post('/api/v1/wallets/keys?passphrase='.$passPhrase, [$key]), true);
    }
    public function changeWalletPassPhrase(string $walletId, string $oldPassPhrase, string $newPassPhrase): array
    {
        return self::jsonDecode($this->post('/api/v1/wallets/password'.$walletId.'?old='.$oldPassPhrase.'&new='.$newPassPhrase), true);
    }
    // Wallet Functions End //

    // Account Functions Start //
    public function getAccount(string $accountId): array
    {
        return self::jsonDecode($this->get('/api/v1/accounts/'.$accountId), true);
    }
    public function updateAccount(string $accountId, array $body):  array
    {
        return self::jsonDecode($this->put('/api/v1/accounts/'.$accountId, $body), true);
    }
    public function deleteAccount(string $accountId):  array
    {
        return self::jsonDecode($this->delete('/api/v1/accounts/'.$accountId), true);
    }
    public function getAccounts(): array
    {
        return self::jsonDecode($this->get('/api/v1/accounts'), true);
    }
    public function createAccount(array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/accounts', $body), true);
    }
    // Account Functions End //
    
    // Address Functions Start //
    public function createAddress(string $body): array
    {
        return self::jsonDecode($this->post('/api/v1/addresses?passphrase='.$passPhrase, [$body]), true);
    }
    public function addressIsValid(string $address): bool
    {
        return $this->get('/api/v1/addresses/'.$address) === 'true';
    }
    // Address Functions End //

    // Profile Functions Start //
    public function getProfile(): array
    {
        return self::jsonDecode($this->get('/api/v1/profile'), true);
    }
    public function updateProfile(array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/profile', $body), true);
    }
    // Profile Functions End //

    // Transaction Functions Start //
    public function createNewTransaction(string $fromAccount, string $toAddress, float $amount, string $body): array
    {
        return self::jsonDecode($this->post('/api/v1/txs/payments/'.$fromAccount.'/'.$toAddress.'/'.$amount, ['groupingPolicy' => $body]), true);
    }
    public function estimateTransactionFee(string $from, string $to, float $amount, array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/txs/fee/'.$from.'/'.$to.'/'.$amount, $body), true);
    }
    public function updateTransaction(string $address, string $transaction, array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/txs/payments/'.$address.'/'.$transaction, $body), true);
    }
    public function getTransactionHistory(string $walletId = null, string $accountId = null, string $address = null, int $skip = null, int $limit = null): array
    {
        $params = [];
        if($walletId !== null)
        $params['walletId'] = $walletId;
        if($accountId !== null)
        $params['accountId'] = $accountId;
        if($address !== null)
        $params['address'] = $address;
        if($skip !== null)
        $params['skip'] = $skip;
        if($limit !== null)
        $params['limit'] = $limit;
        return self::jsonDecode($this->get('/api/v1/txs/histories?'.http_build_query($params)), true);
    }
    // Transaction Functions End //

    // Update Functions Start //
    public function getUpdate(string $accountId): array
    {
        return self::jsonDecode($this->get('/api/v1/update'), true);
    }
    public function postponeLastUpdate(): array
    {
        return self::jsonDecode($this->post('/api/v1/update/postpone'), true);
    }
    public function applyLastUpdate(): array
    {
        return self::jsonDecode($this->post('/api/v1/update/apply'), true);
    }
    // Update Functions End //

    // Redeem Functions Start //
    public function redeemAda(string $passPhrase, array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/redemptions/ada?passphrase='.$passPharse, $body), true);
    }
    public function redeemAdaPaperVending(string $passPhrase, array $body): array
    {
        return self::jsonDecode($this->post('/api/v1/papervend/redemptions/ada?passphrase='.$passPharse, $body), true);
    }
    // Redeem Functions End //

    // Miscellaneous Functions Start //
    public function initializedReport(): array
    {
        return self::jsonDecode($this->post('/api/v1/reporting/initialized'), true);
    }
    public function getSlotsDuration(): int
    {
        return intval($this->get('/api/v1/settings/slots/duration'));
    }
    public function getNodeVerison(): array
    {
        return self::jsonDecode($this->get('/api/v1/settings/version'), true);
    }
    public function getSyncProgress(): array
    {
        return self::jsonDecode($this->get('/api/v1/settings/sync/progress'), true);
    }
    public function importWallet(string $body): array
    {
        return self::jsonDecode($this->post('/api/v1/backup/import', [$body]), true);
    }
    public function exportWallet(string $walletId, string $body): array
    {
        return self::jsonDecode($this->post('/api/v1/backup/export/'.$walletId, [$body]), true);
    }
    public function getInfo(): array
    {
        return self::jsonDecode($this->get('/api/v1/info'), true);
    }
    // Miscellaneous Functions End //
    private static function jsonDecode(string $content): array
    {
        $data = json_decode($content, true);
        if(!is_array($data))
        throw new \Exception($content);
        else
        return $data;
    }
    private function get(string $endPoint): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->host.':'.$this->port.$endPoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
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
    private function put(string $endPoint, array $putFields = []): string
    {
        $ch = curl_init($endPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
    public function delete(string $endPoint, array $deleteFields): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($putFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
}
?>
