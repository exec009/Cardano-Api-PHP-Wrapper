
# Cardano-Api-PHP-Wrapper
Cardano PHP client to integrate Cardano in PHP platforms using Cardano Wallet

## Example Usage

##### To disable ssl verification (for allowing self signed certificate)
```code
$client = new Cardano('https://127.0.0.1', 8090); // (host, port)
```

##### To disable ssl verification (for allowing self signed certificate)
```code
$client = new Cardano('https://127.0.0.1', 8090, true);
```
##### Sample Call
```code
$client->getAccounts();
```
For more info visit https://cardanodocs.com/technical/wallet/api/#/
