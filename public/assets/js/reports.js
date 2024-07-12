
    $('#consult').click(function(e) {
      e.preventDefault(); // Prevent default form submission

      var startDate = $('#start_date').val();
      var endDate = $('#end_date').val();
console.log(startDate);
console.log(endDate)    
    //   $.ajax({
    //     url: "{{ route('your.controller.function') }}", // Replace with your route
    //     type: 'POST',
    //     data: {
    //       start_date: startDate,
    //       end_date: endDate,
    //     },
    //     success: function(response) {
    //       $('#searchResults').html(response); // Update results container
    //     },
    //     error: function(error) {
    //       console.error(error); // Handle errors
    //     }
    //   });
    });