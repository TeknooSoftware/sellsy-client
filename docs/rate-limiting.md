#Teknoo Software - Sellsy client - Sellsy Rate Limiting

##Introduction

Sellsy API has a rate limiting system to prevent abuse of the API. As stated in the documentation, the limit is 5 requests per second.

##Manage Rate Limiting

To manage the rate limiting, a middleware `Transport` can be used to wait between each request to respect the limit:

    ```php
    // Create the middleware Transport
    $transport = new RateLimitTransport($transportBridge);
    // Set the transport bridge
    $sellsy->setTransport($transport);
    ```

##Limits

Use the middleware at your own risk: it can crash very quickly a webserver!

In sleep/usleep, the PHP process is freeze and can not execute another stuff.
The count of php process is limited on PHP-FPM.

If an async behavior is used (like reactphp or fibers), sleep/usleep will block other requests / operations.
