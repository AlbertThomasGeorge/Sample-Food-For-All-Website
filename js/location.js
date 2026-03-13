/*
    const person1 = {
        firstName: "Spongebob",
        lastName: "Squarepants",
        age: 30,
        isEmpployed: true,
        sayHello: function(){console.log("HI I am Spongebob!")},
        eat: function(){console.log("I am eating a Krabby Patty")},
    }
    console.log(person1.firstName);
    console.log(person1.lastName);
    console.log(person1.age);
    console.log(person1.isEmpployed);
    const person2 = {
        firstName: "Patrick",
        lastName: "Star",
        age: 42,
        isEmpployed: false,
        sayHello: () => console.log("Hey, I'm Patrick..."),
        eat: () => console.log("I am eating roast beef, chicken and pizza"),
    }
    console.log(person2.firstName);
    console.log(person2.lastName);
    console.log(person2.age);
    console.log(person2.isEmpployed);
    person1.sayHello();
    person2.sayHello();
    person1.eat();
    person2.eat();
    in above code person1 and person2 are objects
    L is a javascript object that is defined in leaflet.js
    L has a function named map within it, the function takes the id of div where map is to be as a parameter
    L.map('map') where map is id of div returns another object, that object has a function named setView, the setView function has two parameters, first parameter is an array, the array has two values that are latitude and longitude respecively, 
                                                                                                                                                   second parameter is a number that states the zoom level of the map
    the map would be displayed with the latitude and longitude mentioned in array as center initially
    zoom level 0 (1 * 1), the whole world is displayed in one tile, each tile is usually 256px * 256px, but if the div where it is to be displayed has lower dimensions the image is shrinked to fit the div using full available space
    zoom level 1 (2 * 2), the whole world is displayed in four tiles, the tile containing the latitude and longitude mentioned inside setView function is for sure displayed and centered within the div, 
                          if the div is smaller than 256px widthwise and heightwise then only the right tile having latitude and longitude mentioned inside setView function is displayed, however the size of the tile is shrinked to fit the div using full available space,
                          if the div is equal to 256px widthwise and heightwise then only the right tile having latitude and longitude mentioned inside setView function is displayed in original size,
                          if the div is larger than 256px widthwise or heightwise or both multiple tiles are displayed, however not each tile would be complete some tiles may be incomplete depending on div size, the right tile having latitude and longitude mentioned inside setView function is at the center of the div
    zoom level 2 (4 * 4),
    zoom level 3 (8 * 8), and so on
    L.tileLayer(`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`, {attribution: '© OpenStreetMap contributors'}).addTo(map);
    explanation of line of code just above this line
    L has a function named titleLayer, the function has one required parameter and one optional parameter
    https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png, here {s} refers to name of server out of many servers that are used to obtain initial map image or map image dynamically as and when user moves or zooms,
                                                        here {z} refers to zoom level,
                                                        here {x} and {y} are the tile coordinates which refers to which tile to choose
    {attribution: '© OpenStreetMap contributors'}:= this is an object, at the right bottom corner of the map image © OpenStreetMap contributors is always mentioned  
    when map is clicked, an event object is made which is passed to a function as parameter, the event object stores information of the latitude and longitude of where clicked                                         
*/
const map_object = L.map('map');
map_object.setView([21.84, 82.79], 5);
L.tileLayer(`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`, {attribution: '© OpenStreetMap contributors'}).addTo(map_object);
let marker; // if marker is not initialized then if(marker) would return false, if marker is initialized then if(marker) would return true
map_object.on('click', function(e) { 
                           if (marker) map_object.removeLayer(marker); 
                           marker = L.marker(e.latlng); // latlng short for latitude and longitude
                           marker.addTo(map_object); 
                           document.getElementById('latitude').value = e.latlng.lat; 
                           document.getElementById('longitude').value = e.latlng.lng; });


