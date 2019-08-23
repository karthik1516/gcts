( function( $ ) {

    $( document ).ready( function() {

        $( '#add-student-btn' ).on( 'click', function( event ) {
            console.log("Add Student Clicked");
        });
        $( '.register_btn' ).on( 'click', function( event ) {
            console.log("Add Student Clicked");
        });
        total=$('#paypal-button').attr("data-total");

        paypal.Button.render({
            // Configure environment
            env: $('#env').attr('data-val'),
            client: {
              sandbox: 'ARXmOLv1nn594p9dC5M8GufLvnFNhNeoEyhIMNOv8DQZGklIBGzgOY44gOel82B6uubKKIC2oYLi3n2N',
              production: 'AV0hhvUctMCLuXBozaZ7OUSso3O6ytouSkRu5UTc_H91a58dDGGtI0RYuF_znTRR_zTJNAoTiHxdJCUR'
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
              size: 'small',
              color: 'gold',
              shape: 'pill',
            },
        
            // Enable Pay Now checkout flow (optional)
            commit: true,
        
            // Set up a payment
            payment: function(data, actions) {
              return actions.payment.create({
                transactions: [{
                  amount: {
                    'total': total,
                    currency: 'USD'
                  }
                }]
              });
            },
            // Execute the payment
            onAuthorize: function(data, actions) {
              return actions.payment.execute().then(function() {
                $.get( "https://cincytamilsangam.org/878974213789-2/", function( data ) {
                    location.reload();
                  });                  
              });
            }
          }, '#paypal-button');
        

    });

} )(jQuery);    
