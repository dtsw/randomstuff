define(["jquery", "jquery.ui", "d3"], function($, ui, d3) {
    
    return {
        // Public function
        drawChart: function() {
            // Function code goes here
            // set up data
            const data = [10, 15, 20, 25, 8, 7, 89];

            // set up dimensions and margins for the pie chart
            const width = 400;
            const height = 400;
            const margin = 40;

            // set up radius and arc generator
            const radius = Math.min(width, height) / 2 - margin;
            const arc = d3.arc()
            .innerRadius(0)
            .outerRadius(radius);

            // set up pie layout
            const pie = d3.pie()
            .value(d => d)
            .sort(null);

            // set up color scale
            const color = d3.scaleOrdinal()
            .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

            // set up the pie chart container
            const svg = d3.select("#pie-chart")
            .append("svg")
              .attr("width", width)
              .attr("height", height)
            .append("g")
              .attr("transform", `translate(${width / 2}, ${height / 2})`);

            // create pie slices and labels
            const pieData = pie(data);
            const slice = svg.selectAll("slice")
            .data(pieData)
            .enter()
            .append("g")
              .attr("class", "slice");

            slice.append("path")
            .attr("d", arc)
            .attr("fill", d => color(d.data));

            slice.append("text")
            .attr("transform", d => `translate(${arc.centroid(d)})`)
            .attr("text-anchor", "middle")
            .text(d => d.data);

        },

        attachAutocomplete: function() {
            var availableTags = [
                  "ActionScript",
                  "AppleScript",
                  "Asp",
                  "BASIC",
                  "C",
                  "C++",
                  "Clojure",
                  "COBOL",
                  "ColdFusion",
                  "Erlang",
                  "Fortran",
                  "Groovy",
                  "Haskell",
                  "Java",
                  "JavaScript",
                  "Lisp",
                  "Perl",
                  "PHP",
                  "Python",
                  "Ruby",
                  "Scala",
                  "Scheme"
                ];
            $( "#tags" ).autocomplete({
                source: availableTags
            });

        }
    };

});
