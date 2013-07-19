var mapa;
var dymek = new google.maps.InfoWindow(); // zmienna globalna

function dodajMarker(lat,lng,txt)
{
	// tworzymy marker
	var opcjeMarkera =
	{
		position: new google.maps.LatLng(lat,lng),
		map: mapa
	}
	var marker = new google.maps.Marker(opcjeMarkera);
	marker.txt=txt;

	google.maps.event.addListener(marker,"click",function()
	{
		dymek.setContent(marker.txt);
		dymek.open(mapa,marker);
	});
	return marker;
}

function mapaStart()
{
	var wspolrzedne = new google.maps.LatLng(38.121593,-27.020874);
	var opcjeMapy = {
		zoom: 14,
		center: wspolrzedne,
	    panControl: true,
	    zoomControl: true,
	    scaleControl: true,
		zoomControlOptions: {
		  style: google.maps.ZoomControlStyle.LARGE
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	mapa = new google.maps.Map(document.getElementById("google-map"), opcjeMapy);
	var marker1 = dodajMarker(38.121593,-27.020874,'TEST');
	google.maps.event.trigger(marker1,'click');

}
$(document).ready(function(){
 mapaStart();
});


