<?php
$currentDateTime = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Payment Page</title>
    <link rel="stylesheet" href="style.css" />
    <script src="./utils.js"></script>

    <!-- Include apple-pay.js -->
    <script async crossorigin src="https://applepay.cdn-apple.com/jsapi/v1.1.0/apple-pay-sdk.js"></script>
</head>

<body>
    <center>
        <h1>Demo (Card is not charged)</h1>
        <p><strong>Price: $0.1</strong></p>
        <p><?php echo "Current Date and Time: " . $currentDateTime;; ?></p>
        <style>
            apple-pay-button {
                --apple-pay-button-width: 150px;
                --apple-pay-button-height: 30px;
                --apple-pay-button-border-radius: 3px;
                --apple-pay-button-padding: 0px 0px;
                --apple-pay-button-box-sizing: border-box;
            }
        </style>
        <apple-pay-button id="apple-pay-button" buttonstyle="black" type="plain" locale="en-US"></apple-pay-button>
        <div id="messages" role="alert" style="display: none;"></div>
    </center>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {

            if (window.ApplePaySession) {
                addMessage("Apple pay is supported");
                if (ApplePaySession.canMakePayments) {
                    addMessage("payment methods are enabled");
                } else {
                    addMessage("payment methods are not enabled");
                }
                const applePayButton = document.getElementById('apple-pay-button');

                if (applePayButton) {


                    try {

                        //addMessage("Session started " + session);
                        applePayButton.addEventListener('click', () => {
                            var amount = 0.1;
                            const paymentRequest = {
                                countryCode: "US",
                                currencyCode: "USD",
                                merchantCapabilities: ['supports3DS'],
                                supportedNetworks: ["visa", "masterCard", "amex", "discover"],
                                total: {
                                    label: "Demo",
                                    type: "final",
                                    amount: amount
                                }
                            };

                            const session = new ApplePaySession(3, paymentRequest);

                            addMessage("Session started " + session);

                            session.onvalidatemerchant = (event) => {
                                //addMessage('validation url ' + event.validationURL);
                                fetch("apple-pay-session.php", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'validationURL=' + encodeURIComponent(event.validationURL),
                                    })
                                    .then(res => res.json())
                                    .then(merchantSession => {
                                        session.completeMerchantValidation(merchantSession);
                                        addMessage("merchant validation completed" + merchantSession);
                                    })
                                    .catch(err => {
                                        addMessage("Error fetching merchant session: " + err);
                                    });
                            };

                            session.onpaymentauthorized = (event) => {
                                const payment = event.payment;
                                const paymentToken = payment.token.paymentData;
                                // Create an object containing the payment token and the amount
                                const paymentData = {
                                    paymentToken,
                                    amount
                                };
                                // Send payment token to server
                                /*fetch("apple-pay-complete.php", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            paymentData
                                        }),
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        var status;
                                        // Display payment completion status
                                        addMessage("Payment status" + data.status);
                                        if (data.status == 'success') {
                                            status = ApplePaySession.STATUS_SUCCESS;
                                        } else {
                                            status = ApplePaySession.STATUS_FAILURE;
                                        }
                                    })
                                    .catch(err => {
                                        addMessage("Error completing payment: " + err);
                                    });*/

                                session.completePayment(ApplePaySession.STATUS_SUCCESS);
                            };

                            session.begin();

                        });

                    } catch (err) {
                        addMessage("Error: " + err.message);
                    }

                } else {
                    console.error("Apple Pay button element not found.");
                }

            } else {
                addMessage("Error: Apple pay is not supported");
            }
        });
    </script>
</body>

</html>