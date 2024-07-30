<?php
function get_lonlat($address)
{
    try {
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false&key=' . $apiKey);
        $geo = json_decode($geo, true);

        if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
        }
        $data = [
            'latitude' => $latitude ?? null,
            'longitude' => $longitude ?? null
        ];

        return $data;
    } catch (Exception $e) {
    }
}
