$(document).ready(function(){
    $( "#order-form" ).submit(function( event ) {
        event.preventDefault();

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(data)
            {
                var data = jQuery.parseJSON(data);
                $("#nb-product").text(data.nbProduct);
                $("#shoppingcart").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            }
        });
    });
});