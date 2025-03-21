// $(document).ready(function() {            
//     // Get the current URL
//     var currentURL = window.location.href;
//     var segments = currentURL.split('/');
//     segments = segments.filter(function(segment) {
//       return segment !== '';
//     });

//     if(typeof segments[2] !== 'undefined'){
//       console.log(segments[2]);
//         var urlParams = new URLSearchParams(window.location.search);
//         var desiredParam = urlParams.get("search");
//         if (desiredParam !== null) {
//             table.search(desiredParam).draw();
//         }
//     }
// });