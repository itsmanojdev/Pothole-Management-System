import "./bootstrap";
import "./helper";
import "./constants";
import Alpine from "alpinejs";
import Precognition from "laravel-precognition-alpine";
import toastr, { clear } from "toastr";
import "toastr/build/toastr.min.css";

import.meta.glob(['../images/**', '../audio/**']);

window.Alpine = Alpine;

Alpine.plugin(Precognition);
Alpine.start();

/***********************
 * Toastr config *
 **********************/
toastr.options.newestOnTop = true;
toastr.options.progressBar = true;

window.toastr = toastr;

/***********************
 * Block Small Screen *
 **********************/
const blockSmallScreen = () => {
    let blockDiv = document.querySelector("#block-access");

    if(window.innerWidth < 600){
        blockDiv.classList.add("flex");
        blockDiv.classList.remove("hidden");
    }else{
        blockDiv.classList.add("hidden");
        blockDiv.classList.remove("flex");
    }
}

window.addEventListener("load", blockSmallScreen);
window.addEventListener("resize", blockSmallScreen);

/***********************
 * Google Map *
 **********************/
let map, marker;

/**
 * Mode: create -> Marker: Current Location, Update Marker:Yes
 * Mode: show -> Marker: DB Input, Update Marker:No
 * Mode: edit -> Marker: DB Input, Update Marker:Yes
 *
 * @param {string} mode
 */
async function initMap(mode = "create") {
    let isCreate = mode == "create";
    let isShow = mode == "show";
    let isEdit = mode == "edit";

    const addressInput = document.querySelector("#address-input");
    const latitudeInput = document.querySelector("#latitude");
    const longitudeInput = document.querySelector("#longitude");
    const addressDropdown = document.querySelector("#address-dropdown-list");
    let isAddressValid = isEdit || isShow ? true : false;

    // Import Required Libraries
    const [{ Map }, { AdvancedMarkerElement }, Place] = await Promise.all([
        google.maps.importLibrary("maps"),
        google.maps.importLibrary("marker"),
        google.maps.importLibrary("places"),
    ]);
    const Geocoder = new google.maps.Geocoder();

    // Default Position - Center of India
    const position = { lat: 23.5120135, lng: 80.3263802 };

    // Rendering Map
    map = new Map(document.getElementById("pothole-create-map"), {
        zoom: 18,
        center: position,
        mapId: "pothole_create",
    });

    if (isShow || isEdit) {
        console.log(latitudeInput.value, longitudeInput.value);
        const pos = {
            lat: parseFloat(latitudeInput.value),
            lng: parseFloat(longitudeInput.value),
        };
        console.log(pos);

        updateMarkerOnMap(map, marker, pos);
    } else if (isCreate) {
        // Current Location Marker
        currentLocationMarker(map);
    }

    if (isCreate || isEdit) {
        // Pan to Current Location on btn Click Event
        document
            .querySelector("#current-location-btn")
            .addEventListener("click", () => currentLocationMarker(map));

        // Change Marker on Click Event
        map.addListener("click", (e) => {
            updateInputFromPos(e.latLng);
            updateMarkerOnMap(map, marker, e.latLng);
        });

        // Address Field Handler - Autocomplete
        var newestRequestId = 0;
        var request = {
            input: addressInput.value,
            region: "in",
            sessionToken: new Place.AutocompleteSessionToken(),
        };

        addressInput.addEventListener("input", makeAutocompleteRequest);

        addressInput.addEventListener("keypress", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                newestRequestId++;

                // geocoding and update input field
                Geocoder.geocode(
                    { address: event.target.value },
                    function (results, status) {
                        if (status == "OK") {
                            let pos = results[0].geometry.location;
                            isAddressValid = true;
                            addressInput.value = results[0].formatted_address;
                            updateMarkerOnMap(map, marker, pos);
                        } else {
                            clearInputs();
                            toast(
                                "error",
                                "No location found for the given address.",
                            );
                        }
                    },
                );

                // change lat long input
                addressDropdown.replaceChildren();
            }
        });

        //Clear address dropdown when clicked outside the dropdown
        document.addEventListener("click", (e) => {
            if (!e.target.closest(".relative")) {
                if (!isAddressValid) {
                    clearInputs();
                }
                addressDropdown.replaceChildren();
            }
        });
    }

    // Mark Currect Location on Map
    function currentLocationMarker(map) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    updateInputFromPos(pos);
                    updateMarkerOnMap(map, marker, pos);
                },
                () => {
                    clearInputs();
                    toast("error", "Error: The Geolocation service failed.");
                },
            );
        } else {
            // Browser doesn't support Geolocation
            clearInputs();
            toast("error", "Error: Your browser doesn't support geolocation.");
        }
    }

    //Update address input fields
    function updateInputFromPos(pos) {
        Geocoder.geocode({ location: pos }, function (results, status) {
            if (status == "OK") {
                isAddressValid = true;
                addressInput.value = results[0].formatted_address;
            } else {
                clearInputs();
                toast("error", "Failed to fetch address. Please try again");
            }
        });
    }

    //Clear address input fields
    function clearInputs() {
        isAddressValid = false;
        addressInput.value = "";
        latitudeInput.value = "";
        longitudeInput.value = "";
    }

    // Update Marker
    function updateMarkerOnMap(currentMap, prevMarker, pos) {
        // Remove Current Marker
        if (prevMarker) prevMarker.setMap(null);

        // Place Update Marker
        marker = new AdvancedMarkerElement({
            map: currentMap,
            position: pos,
            title: "Pothole Location",
        });

        // Go to updated position on Map
        map.panTo(pos);

        // Form Input Update
        latitudeInput.value =
            typeof pos.lat === "function" ? pos.lat() : pos.lat;
        longitudeInput.value =
            typeof pos.lng === "function" ? pos.lng() : pos.lng;
    }

    //Address Auto Complete
    async function makeAutocompleteRequest(inputEvent) {
        // To avoid race conditions, store the request ID and compare after the request.
        const requestId = ++newestRequestId;

        // Reset elements and exit if an empty string is received.
        if (inputEvent.target.value == "") {
            clearInputs();
            addressDropdown.replaceChildren();
            return;
        }

        // Add the latest input to the request.
        request.input = inputEvent.target.value;

        // Fetch autocomplete suggestions and show them in a list.
        const { suggestions } =
            await Place.AutocompleteSuggestion.fetchAutocompleteSuggestions(
                request,
            );

        // Clear the list first
        addressDropdown.replaceChildren();

        for (const suggestion of suggestions) {
            const placePrediction = suggestion.placePrediction;
            const place = placePrediction.toPlace();

            await place.fetchFields({
                fields: ["displayName", "formattedAddress", "location"],
            });

            // Create a new list item element.
            const li = document.createElement("li");
            li.innerHTML = `
                <div class="flex gap-2 p-1.5 border-b border-gray-300">
                    <div class="pt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 -960 960 960" class="place-autocomplete-element-place-icon-default-pin"><path d="M480-80q-14 0-24-8t-15-21q-19-56-47.5-105T314-329q-51-66-82.5-126T200-600q0-117 81.5-198.5T480-880q117 0 198.5 81.5T760-600q0 91-34.5 151.5T646-329q-54 72-81.5 119.5T519-109q-5 14-15.5 21.5T480-80Zm0-143q17-34 38.5-67t63.5-88q43-56 70.5-103.5T680-600q0-83-58.5-141.5T480-800q-83 0-141.5 58.5T280-600q0 71 27.5 118.5T378-378q42 55 63.5 88t38.5 67Zm0-277q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm0-100Z"></path></svg>
                    </div>
                    <div class="">
                        <p class="text-sm">${place.displayName}</p>
                        <p class="text-xs text-muted">${place.formattedAddress}</p>
                    </div>
                </div>`;

            // List item event listener
            li.addEventListener("click", () => {
                isAddressValid = true;
                addressInput.value = place.formattedAddress;
                updateMarkerOnMap(map, marker, place.location);
                addressDropdown.replaceChildren();
            });

            // Race Condition Handler - if current request matches lastest request, update dropdown list, else do not render further
            if (requestId === newestRequestId) addressDropdown.appendChild(li);
            else return;
        }
    }
}

window.initMap = initMap;
