$(document).ready(function(){
/**
     *  Product actions
     * */
    // Unpublish Product 
    function activateUser(user_id) {
        swal(
              {
                title: "Unpublished?",
                text: "Confirm you want to unpublish this product",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, unpublish it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'GET',
                      url:  "/unpublish-product/"+ user_id,
                      success: function() {
                          //swal.hideLoading()
                          swal(
                            {
                              title: "Unpublished!",
                              text: "The product has been unpublished successfully.",
                              type: "success",
                            },
                            function(isOk) {
                              if (isOk) {
                                document.location.reload();
                              }
                            }
                          );
                           
                      }
                  });
                } else {
                  swal("Cancelled", "The product is not unpublished", "error");
                }
              }
          );
    }
      $('#deactivate_user').click(function() {
          var id = $(this).attr("user-id");console.log(id);
          swal(
              {
                title: "Unpublished?",
                text: "Confirm you want to unpublish this product",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, unpublish it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'GET',
                      url:  "/unpublish-product/"+ id,
                      success: function() {
                          //swal.hideLoading()
                          swal(
                            {
                              title: "Unpublished!",
                              text: "The product has been unpublished successfully.",
                              type: "success",
                            },
                            function(isOk) {
                              if (isOk) {
                                document.location.reload();
                              }
                            }
                          );
                           
                      }
                  });
                } else {
                  swal("Cancelled", "The product is not unpublished", "error");
                }
              }
          );
      });
});

$(document).ready(function(){
          $('.activate_user').click(function() {
          console.log('id');
        });
        });