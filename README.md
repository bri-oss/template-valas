# Template-informasi-rekening-php

This is a simple template for Virtual Account SNAP BI using PHP.

module:
- [Virtual Account - Briva Online](https://developers.bri.co.id/en/snap-bi/apidocs-virtual-account-briva-online-snap-bi)
- [Virtual Account - Briva WS](https://developers.bri.co.id/en/snap-bi/apidocs-virtual-account-briva-ws-snap-bi)

## List of Content
- [Instalasi](#instalasi)
  - [Prerequisites](#prerequisites)
  - [How to Setup Project](#how-to-setup-project)
  - [Payment](#payment)
- [Caution](#caution)
- [Disclaimer](#disclaimer)

## Instalasi

### Prerequisites
- php
- composer

### How to Setup Project

```bash
1. copy .env file by typing 'cp .env.example .env' in the terminal
2. fill the .env file with the required values
3. run composer install to install all dependencies
```

### Payment
```bash
1. fill partnerId and channelId
2. fill partnerServiceId example '   55888'
3. fill customerNo by default this template give you utils that can generate customerNo
4. fill inquiryRequestId example 'e3bcb9a2-e253-40c6-aa77-d72cc138b744'
5. fill value example 100000.00
6. fill currency by default is IDR
7. fill trxDateInit by default this template give you utils that can generate it example 2021-11-25T15:01:07+07:00
8. fill sourceBankCode example 0002
9. fill passApp example 'b7aee423dc7489dfa868426e5c950c677925'
10. fill idApp example 'test'
11. run command `php src/va_online_inquiry.php serve`
```

## Caution

Please delete the .env file before pushing to github or bitbucket

## Disclaimer

Please note that this project is just a template on the use of BRI-API php sdk and may have bugs or errors.
