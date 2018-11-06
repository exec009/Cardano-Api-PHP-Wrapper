

## **Cardano-Api-PHP-Wrapper** 


A PHP client to integrate the Cardano SL Wallet into PHP applications. 
This library uses v1 of the Cardano API.


### **Get a Wallet First**


To use this library will need to have a working wallet to interact with running on your local host. If you dont already have one, start here: 

#### **Linux**

https://github.com/input-output-hk/cardano-sl/blob/develop/docs/how-to/build-cardano-sl-and-daedalus-from-source-code.md

*Note for Linux users, you don't need to build Daedalus, you only need to build the Cardano SL node, it has a wallet server.* 

#### **OSX/Windows**

https://daedaluswallet.io/


### **Example Usage**


##### **Initialization (w/ SSL verification)**

The 'true' option enables SSL verification. It past versions you were allowed to use a self-signed certificate with the 'false' option or no option, that is no longer allowed, you must now use SSL.

In order to use SSL you will need to edit the class and define the path to the wallet certificate, most likely located here: /path/to/state-wallet-mainnet/tls/client/client.pem 

```code
$client = new Cardano('https://127.0.0.1', 8090, true); // (host, port)
```

##### **Sample Call**
```code
$client->getAccounts();
```

>For more information on the Cardano v1 API visit https://cardanodocs.com/technical/wallet/api/v1/
