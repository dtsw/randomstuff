define(["jquery", "jquery.ui", "d3", "myalert"], function($, ui, d3, myalert) {
    //the jquery and plugins have been loaded.
    $(function() {

      myalert.drawChart();
      myalert.attachAutocomplete();
      
      $('body').append($("<p>").html("Hello World"));

    });

});
