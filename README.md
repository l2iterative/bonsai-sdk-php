# PHP SDK for Bonsai API

<img src="title.png" align="right" alt="a guy sitting on the subway station waiting for the train to arrive" width="300"/>

The current Bonsai API implementation is written in Rust. Since Bonsai requires an API key and would consume the quotas
to generate the zero-knowledge proof, web3 applications built with Bonsai would need a backend.

Like most web3 applications, the backend can be **_minimalistic_**, because most of the on-chain interactions can be completed 
through, for example, [Metamask's Ethereum provider API](https://docs.metamask.io/wallet/reference/provider-api/), in the frontend.

## How would the backend look like?

Therefore, backends often fall into the following six classes:
- Pure:
  * A pure Typescript implementation, probably with the use of WebAssembly, often implemented through Rust (see [wasm-bindgen](https://github.com/rustwasm/wasm-bindgen)) 
  * A pure PHP implementation  
  * A pure Python implementation, such as [Django](https://www.djangoproject.com/) and [Flask](https://github.com/pallets/flask)
- With  [foreign function interface (FFI)](https://en.wikipedia.org/wiki/Foreign_function_interface):
  * A Typescript implementation with FFI to high-performance code likely written in Rust, through [ffi-rs](https://github.com/zhangyuang/node-ffi-rs) or [koffi](https://github.com/Koromix/koffi)
  * A PHP implementation using FFI, probably through [the FFI extension](https://www.php.net/manual/en/book.ffi.php)
  * A Python implementation using FFI, probably through [CFFI](https://cffi.readthedocs.io/en/latest/)

Numerous factors will affect which one to go:
- **Availability of web hosting providers.**
  * There are many affordable web hosting platforms for Typescript and PHP. 
  * To run Python, the tradition is to use a full-fledged cloud virtual machine (such as an AWS EC2 instance). There is a preference to 
  avoid using a full-fledged VM due to cost and ability to auto-scale.
- **Performance.** 
  * Everyone would agree that Python is slow. 
  * PHP can be optimized through the use of [OPcache](https://www.php.net/manual/en/book.opcache.php) 
to precompile the script, but it is not machine code. Typescript can be compiled (together with WebAssembly) 
into machine code through [v8](https://v8.dev/), which we can expect to have the best performance among the "pure" group. 
  * Nevertheless, it becomes less relevant when we can use FFI because one can also run machine code, and unlike code 
generated by [just-in-time compilation (JIT)](https://en.wikipedia.org/wiki/Just-in-time_compilation), such code is 
[ahead-of-time compilation (AOT)](https://en.wikipedia.org/wiki/Ahead-of-time_compilation), and is excepted to be more 
performant and should be the go-to whenever available.
- **Developer friendliness.** 
  * Probably most important: ideally with few learning cycles, no hacking around.
  * Rust is useful here, as RISC Zero programs are now written in Rust, and WebAssembly from Rust has been 
used in web development, particularly for cryptography.
  * The other programming language is better similar to Rust. Among all the three, PHP is most similar. PHP is known for very friendly to beginners.
  * All there have available Web3 SDK implementation, but Typescript has best support nowadays. 

It is important to know that this backend is going to be minimalistic. We have the backend because the API key cannot be 
exposed to the client. The backend may just consist of:
- access to a database, such as [MySQL](https://www.mysql.com/), for off-chain data storage
- integration with a spam prevention system, such as [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) or [Google reCAPTCHA](https://www.google.com/recaptcha/about/)
- connecting to RISC Zero Bonsai API, which is the purpose of this repository

## How to use

We would soon add a comprehensive documentation for the PHP API. But it is designed to resemble the Bonsai Rust API here:

<center>
https://github.com/risc0/risc0/blob/main/bonsai/sdk/src/alpha.rs
</center>

The integration tests can be served as tutorials. It can be as simple as follows:
```php
<?php

use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\SessionId;
use L2Iterative\BonsaiSDK\SnarkId;

$client = new Client("https://api.bonsai.xyz", $test_key, $test_version);

$input_id = $client->upload_input($input_data);
$session_id = new SessionId($client->create_session($test_img_id, $input_id, []));

$status = NULL;
while(true) {
    usleep(300);
    $status = $session_id->status($client);
    
    if($status->status != 'RUNNING') {
        break;
    }
}

// error handling if the status is not successful

$snark_id = new SnarkId($client->create_snark($session_id->uuid));

$status = NULL;
while(true) {
    usleep(300);
    $status = $snark_id->status($client);
    
    if($status->status != 'RUNNING') {
        break;
    }
}

// error handling if the status is not successful

// the proof can be forwarded to the frontend to make RPC calls

```

## Future work

A strictly typed serializer would be useful for developers to assemble the inputs for RISC Zero Bonsai proof generation 
without the necessity of going through FFI to let Rust serialize. This would be added in a future date.