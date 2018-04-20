
##################################
##### Cardano-Api-PHP-Wrapper ####
##################################

A PHP client to integrate the Cardano SL Wallet into PHP applications. 
This library uses v1 of the Cardano API.

##############################
##### Get a Wallet First! ####
##############################

To you this library will need to have a working wallet to interact with running on your local host. If you dont already have one, start here: 

##### Linux 

https://github.com/input-output-hk/cardano-sl/blob/develop/docs/how-to/build-cardano-sl-and-daedalus-from-source-code.md

Note for Linux users, you don't need to build Daedalus, you only need to build the Cardano SL node, it has a wallet server. 

##### OSX/Windows

https://daedaluswallet.io/

########################
##### Example Usage ####
########################

##### Default initialization (with ssl verification)
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
For more information on the Cardano v1 API visit https://cardanodocs.com/technical/wallet/api/v1/
