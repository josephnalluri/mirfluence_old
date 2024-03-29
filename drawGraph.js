function createGraph(jsonData,tabDiv,counterID)
{
  
 // Checking the parameters
 // console.log(jsonData);
 // console.log(tabDiv);

  jsonData.sort(function(a,b){if(a.source>b.source){return 1;}
  else if(a.source<b.source){return-1;}
  else{
    if(a.target>b.target){return 1;}

    if(a.target<b.target) {
      return-1;
    }

    else{
      return 0;
    }
  }
});

for(var i=0;i<jsonData.length;i++) {
  if(i!=0&&jsonData[i].source==jsonData[i-1].source&&jsonData[i].target==jsonData[i-1].target) {
    jsonData[i].linknum=jsonData[i-1].linknum+1;
  }
  else
  {
    jsonData[i].linknum=1;
  }
}
    var nodes={};
    jsonData.forEach(function(link){
      link.source = nodes[link.source] || (nodes[link.source]={name:link.source});
      link.target = nodes[link.target] || (nodes[link.target]={name:link.target});
      //console.log(nodes[link.source], typeof nodes[link.target]);
      //console.log(nodes);
    });

    //console.log(Object.keys(nodes));
    for(var k in nodes) {
      if(nodes[k].name.indexOf("hsa") > -1) {
        nodes[k]["group"] = 0;
      }
      else {
        nodes[k]["group"] = 1;
      }
    }
    var arrows=[];

    for(var i=0;i<jsonData.length;i++){arrows[i]=jsonData[i].type;};

    var w= 900, h=800;

   // var w = document.getElementById("graph").offsetWidth;
   
    // Switch case to associate variable 'w' with the correct calling object.
    // If the function drawGraph is called from tab 'Individual Disease', v = #graph object
    // If the funtion drawGraph is called from tab 'Disease Category', v = #disease_category_graph object 
    switch(tabDiv)
    {
      case "#graph": var w = document.getElementById("graph").offsetWidth; break;
      case "#disease_category_graph": var w = document.getElementById("disease_category_graph").offsetWidth; break;
<<<<<<< HEAD
      case "#single_dis_graph": var w = document.getElementById("single_dis_graph").offsetWidth; break;
=======
<<<<<<< HEAD
      case "#single_dis_graph": var w = document.getElementById("single_dis_graph").offsetWidth; break;
=======
<<<<<<< HEAD
      case "#single_dis_graph": var w = document.getElementById("single_dis_graph").offsetWidth; break;
=======
<<<<<<< HEAD
      case "#single_dis_graph": var w = document.getElementById("single_dis_graph").offsetWidth; break;
=======
<<<<<<< HEAD
      case "#single_dis_graph": var w = document.getElementById("single_dis_graph").offsetWidth; break;
=======
>>>>>>> 439bc48c12e9af80487f6d150d974a78a89c8d66
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
>>>>>>> 4e8afc97dd42232c3c41181fdb08a3552582e99c
    }
    
    var force=d3.layout.force()
    .nodes(d3.values(nodes))
    .links(jsonData)
    .size([w,h])
    .linkDistance(300)
    .charge(-300)
    .on("tick",tick)
    .start();

    var color = d3.scale.category10();

    var svg=d3.select(tabDiv)
    .append("svg:svg")
    .attr("width",w)
    .attr("height",h);

    svg.append("svg:defs").selectAll("marker")
    .data(arrows)
    .enter().append("svg:marker")
    .attr("id",String).attr("viewBox","0 -5 10 10")
    .attr("refX",15)
    .attr("refY",-1.5)
    .attr("markerWidth",16)
    .attr("markerHeight",16)
    .attr("orient","auto")
    .append("svg:path")
    .attr("d","M0,-5L10,0L0,5");

    var path=svg.append("svg:g").selectAll("path")
    .data(force.links())
    .enter().append("svg:path")
    .attr("class",function(d){return"link "+d.type;})
    .attr("marker-end",function(d){return"url(#"+d.type+")";});

    var circle=svg.append("svg:g").selectAll("circle")
    .data(force.nodes())
    .enter().append("svg:circle")
    .attr("r",8)
    .style("fill", function(d) {
      return color(d.group);
    })
    .call(force.drag);

    var text=svg.append("svg:g").selectAll("g")
    .data(force.nodes())
    .enter().append("svg:g");
    text.append("svg:text")
    .attr("x",8)
    .attr("y",".31em")
    .attr("class","shadow")
    .text(function(d){return d.name;});

    text.append("svg:text")
    .attr("x",8).attr("y",".31em")
    .text(function(d){return d.name;});

    function tick() {
        path.attr("d",function(d)
        {
          var dx=d.target.x-d.source.x,dy=d.target.y-d.source.y,
          dr=1000/d.linknum;
          return"M"+d.source.x+","+d.source.y+"A"+dr+","+dr+" 0 0,1 "+d.target.x+","+d.target.y;
        });

      circle.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});

      text.attr("transform",function(d){return"translate("+d.x+","+d.y+")";});
    }
  }
