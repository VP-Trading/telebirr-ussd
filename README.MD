<p align="center"><a href="https://vptrading.et"><img src="/imgs/logo.png" alt="VP Logo"></a></p>

<h1 align="center">Laravel Package For<br> Telebirr USSD</h1>

# Introduction

This Laravel package is a featherweight package to integrate Telebirr USSD<sup>&reg;</sup>.

# Usage Guide

### Step One

Run `composer require vptrading/telebirr-ussd-laravel`

### Step Two

Run the artisan command to publish the Vptrading\TelebirrUssd configuration file.

```
php artisan vendor:publish --provider="Vptrading\TelebirrUssd\TelebirrUssdServiceProvider"
```

After running that command you will see a `telebirr.php` configuration file in your application's `config` directory.

### Step Three

Open the `telebirr.php` configuration file and add the key's provided to you from Telebirr<sup>&reg;</sup>

# Usage

## Push

In order to send a buy request using Telebirr<sup>&reg;</sup> all you have to do is import the `TelebirrUssd` Facade where you want to use it and call the `push` method. The `TelebirrUssd::push()` method accepts three parameters, these are: Price, Phone, and Reference Number.

**Example**

```
use Vptrading\TelebirrUssd\Facades\TelebirrUssd;

$response = TelebirrUssd::push(1, '0912345678', 'VP_212fw323r3');
```

> Note: The `ShortCode` is provided by Ethio Telecom

When calling that method it return a response

**Response Example**

```
{
    "Body": {
        "Response": {
            "Header": {
                "Version": "1.0",
                "OriginatorConversationID": "VP_212fw323r",
                "ConversationID": "AG_20231118_70406b41c7f87f04c861"
            },
            "Body": {
                "ResponseCode": "0",
                "ResponseDesc": "Accept the service request successfully.",
                "ServiceStatus": "0"
            }
        }
    }
}
```

> Note: The `OriginatorConversationID` is the reference number you used to as the `RefNo`

This will send a push ussd to the phone number you used. Once the user fills their password and confirmed the payment Telebirr will send a notification.

## Deconstruct

The next is being notified when a payment is successfull. After the user has paid the amount described, Telebirr<sup>&reg;</sup> will send you a notification on the Result URL you specified in the `telebirr.php` config file.

**Example Notification String From Telebirr<sup>&reg;</sup>**

```
<?xml version="1.0" encoding="UTF-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Body><api:Result xmlns:api="http://cps.huawei.com/cpsinterface/api_resultmgr" xmlns:res="http://cps.huawei.com/cpsinterface/result" xmlns:com="http://cps.huawei.com/cpsinterface/common"><res:Header><res:Version>1.0</res:Version><res:OriginatorConversationID>VP_212fw323r</res:OriginatorConversationID><res:ConversationID>AG_20231017_70101ef1abddd338afdf</res:ConversationID></res:Header><res:Body><res:ResultType>0</res:ResultType><res:ResultCode>0</res:ResultCode><res:ResultDesc>Process service request successfully.</res:ResultDesc><res:TransactionResult><res:TransactionID>AJH40OGEZK</res:TransactionID><res:ResultParameters><res:ResultParameter><com:Key>DebitBalance</com:Key><com:Value>{"total":[{"amount":"3462258.96","currency":"ETB"}],"list":[{"amount":"0.00","accountno":"100000000111325021","currency":"ETB"},{"amount":"4087.35","accountno":"100000000110941240","currency":"ETB","accounttypename":"Customer Incentive Account"},{"amount":"904075.30","accountno":"100000000110261011","currency":"ETB","accounttypename":"Customer E-Money Account"},{"amount":"0.00","accountno":"100000000110941232","currency":"ETB","accounttypename":"Incentive Account"},{"amount":"1001010.00","accountno":"100000000111442800","currency":"ETB","accounttypename":"Customer E money Account"},{"amount":"3086.31","accountno":"100000000111661169","currency":"ETB","accounttypename":"Customer E-Money Account For Fuel Coupon"},{"amount":"500000.00","accountno":"100000000111121768","currency":"ETB"},{"amount":"0.00","accountno":"100000000111043129","currency":"ETB"},{"amount":"1050000.00","accountno":"100000000111561013","currency":"ETB","accounttypename":"Customer Emoney Account"}]}</com:Value></res:ResultParameter><res:ResultParameter><com:Key>CreditBalance</com:Key><com:Value/></res:ResultParameter></res:ResultParameters></res:TransactionResult></res:Body></api:Result></soapenv:Body></soapenv:Envelope>
```

In order to decode this, the package provides a `deconstruct` method. All you need to do is put the notification string sent from Telebirr<sup>&reg;</sup> in to that static method and it will be decoded.

**Example**

```
use Vptrading\TelebirrUssd\Facades\TelebirrUssd;

$decoded = TelebirrUssd::deconstruct('<?xml version="1.0" encoding="UTF-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Body><api:Result xmlns:api="http://cps.huawei.com/cpsinterface/api_resultmgr" xmlns:res="http://cps.huawei.com/cpsinterface/result" xmlns:com="http://cps.huawei.com/cpsinterface/common"><res:Header><res:Version>1.0</res:Version><res:OriginatorConversationID>VP_212fw323r</res:OriginatorConversationID><res:ConversationID>AG_20231017_70101ef1abddd338afdf</res:ConversationID></res:Header><res:Body><res:ResultType>0</res:ResultType><res:ResultCode>0</res:ResultCode><res:ResultDesc>Process service request successfully.</res:ResultDesc><res:TransactionResult><res:TransactionID>AJH40OGEZK</res:TransactionID><res:ResultParameters><res:ResultParameter><com:Key>DebitBalance</com:Key><com:Value>{"total":[{"amount":"3462258.96","currency":"ETB"}],"list":[{"amount":"0.00","accountno":"100000000111325021","currency":"ETB"},{"amount":"4087.35","accountno":"100000000110941240","currency":"ETB","accounttypename":"Customer Incentive Account"},{"amount":"904075.30","accountno":"100000000110261011","currency":"ETB","accounttypename":"Customer E-Money Account"},{"amount":"0.00","accountno":"100000000110941232","currency":"ETB","accounttypename":"Incentive Account"},{"amount":"1001010.00","accountno":"100000000111442800","currency":"ETB","accounttypename":"Customer E money Account"},{"amount":"3086.31","accountno":"100000000111661169","currency":"ETB","accounttypename":"Customer E-Money Account For Fuel Coupon"},{"amount":"500000.00","accountno":"100000000111121768","currency":"ETB"},{"amount":"0.00","accountno":"100000000111043129","currency":"ETB"},{"amount":"1050000.00","accountno":"100000000111561013","currency":"ETB","accounttypename":"Customer Emoney Account"}]}</com:Value></res:ResultParameter><res:ResultParameter><com:Key>CreditBalance</com:Key><com:Value/></res:ResultParameter></res:ResultParameters></res:TransactionResult></res:Body></api:Result></soapenv:Body></soapenv:Envelope>');
```

**Result**

```
{
    "Body": {
        "Result": {
            "Header": {
                "Version": "1.0",
                "OriginatorConversationID": "VP_212fw323r",
                "ConversationID": "AG_20231118_70406b41c7f87f04c861"
            },
            "Body": {
                "ResultType": "0",
                "ResultCode": "0",
                "ResultDesc": "Process service request successfully.",
                "TransactionResult": {
                    "TransactionID": "AKI2AGO8RM",
                    "ResultParameters": {
                        "ResultParameter": [
                            {
                                "Key": "DebitBalance",
                                "Value": "{\"total\":[{\"amount\":\"15.17\",\"currency\":\"ETB\"}],\"list\":[{\"amount\":\"0.30\",\"accountno\":\"100000000114997859\",\"currency\":\"ETB\",\"accounttypename\":\"Customer Incentive Account\"},{\"amount\":\"14.87\",\"accountno\":\"100000000114997842\",\"currency\":\"ETB\",\"accounttypename\":\"Customer E-Money Account\"}]}"
                            },
                            {
                                "Key": "CreditBalance",
                                "Value": []
                            }
                        ]
                    }
                }
            }
        }
    }
}
```

The result is a json with the unique `OriginatorConversationID` for you to query your database with.

**_🚀 And that's it. Do your thing and Give us a star if this helped you.🚀_**
