/**
 * MySmartSCart - Leaflet Map (FREE - No API Key Required)
 * Uses OpenStreetMap tiles
 */

(function() {
    "use strict";

    // Initialize map when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        var mapElement = document.getElementById("map");
        
        if (!mapElement) {
            console.log('Map element not found');
            return;
        }

        // Get coordinates from data attributes
        var lat = parseFloat(mapElement.getAttribute('data-lat')) || 28.6139; // Default Delhi
        var lng = parseFloat(mapElement.getAttribute('data-lng')) || 77.2090;
        var address = mapElement.getAttribute('data-address') || 'India';
        var zoom = parseInt(mapElement.getAttribute('data-zoom')) || 15;

        // Set map height if not set
        if (!mapElement.style.height || mapElement.style.height === '0px') {
            mapElement.style.height = '400px';
        }

        try {
            // Initialize map
            var map = L.map('map', {
                center: [lat, lng],
                zoom: zoom,
                scrollWheelZoom: false
            });

            // Add OpenStreetMap tiles (FREE - No API Key)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Custom marker icon
            var customIcon = L.divIcon({
                className: 'custom-map-marker',
                html: '<div style="background: linear-gradient(135deg, #1a237e, #3949ab); width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30]
            });

            // Add marker
            var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Popup content
            var popupContent = '<div class="map-popup">' +
                '<strong>MySmartSCart</strong><br>' +
                '<address style="margin: 10px 0; font-style: normal;">' + 
                address.replace(/,/g, '<br>') + 
                '</address>' +
                '<a href="https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng + '" ' +
                'target="_blank" style="color: #1a237e; font-weight: bold;">' +
                '📍 Get Directions</a>' +
                '</div>';

            marker.bindPopup(popupContent);

            // Open popup by default
            setTimeout(function() {
                marker.openPopup();
            }, 500);

            // Enable scroll zoom on click
            map.on('click', function() {
                map.scrollWheelZoom.enable();
            });

            // Disable scroll zoom when mouse leaves
            map.on('mouseout', function() {
                map.scrollWheelZoom.disable();
            });

            console.log('Map initialized successfully at:', lat, lng);

        } catch (error) {
            console.error('Map initialization error:', error);
            // Show fallback message
            mapElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 400px; background: #f5f5f5; color: #666;">' +
                '<div style="text-align: center;">' +
                '<i class="fas fa-map-marker-alt" style="font-size: 48px; color: #1a237e; margin-bottom: 15px;"></i>' +
                '<p><strong>' + address + '</strong></p>' +
                '<a href="https://www.google.com/maps?q=' + lat + ',' + lng + '" target="_blank" class="btn btn-primary">View on Google Maps</a>' +
                '</div></div>';
        }
    });
})();

