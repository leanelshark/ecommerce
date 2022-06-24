<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
    <script src="https://www.paypal.com/sdk/js?client-id=ARxdzpJlncaFidGz6AmwV3mfif3n-yZ2vbDgT7R0Fkdgoe0_TWqhtxMmERG_0vMjWBPtxlGNQjhtl8zi&components=buttons"></script>
   
</head>
<body>

<div id="paypal-button-container"></div>



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>


<script>
  paypal.Buttons({
    createOrder: function(data, actions) {
      // Set up the transaction
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '100.00'
          }
        }]
      });
    },
    style: {
    layout: 'vertical',
    color:  'blue',
    shape:  'rect',
    label:  'paypal'
  },
  onApprove: function(data, actions) {
    // This function captures the funds from the transaction.
    return actions.order.capture().then(function(details) {
      // This function shows a transaction success message to your buyer.
      window.location.href = "completdo.html";
      
    });
  },
  onCancel: function (data) {
    // Show a cancel page, or return to cart
    window.location.href = "completdo.html";
  },
  onError: function (err) {
    // For example, redirect to a specific error page
    window.location.href = "completdo.html";
  }
}).render('#paypal-button-container');

</script>
    
</body>
</html>


