/*   
   Name: Kenish Udash
   ID: 2408031 
*/

// Getting the API key and API URL from the OpenWeatherMap
const apiKey = "e095904cfd08803eaec21e89730df270";
const apiUrl = "http://localhost/Kenishudashprototype/api.php?q=";

// Selecting elements from the DOM
const searchbox = document.querySelector(".search-box input");
const searchbtn = document.querySelector(".search-box button");
const weatherIcon = document.querySelector(".weather-icon");

// Defining the variable for the selected city
let selectedCity;

// Function to check weather based on city input
async function checkWeather(city) {
    try {
        // Fetching weather data from the OpenWeatherMap API
        const response = await fetch(apiUrl + city);

        // Checking if the response status is okay (HTTP status code 200-299)
        if (!response.ok) {
            if (response.status === 404) {
                throw new Error(`City not found: ${city}`);
            } else {
                throw new Error(`Error fetching weather data: ${response.status} ${response.statusText}`);
            }
        }

        // Adding a delay to simulate asynchronous behavior
        setTimeout(async () => {
            // Parsing the JSON response
            const data = await response.json();

            // Logging the fetched data for debugging
            console.log(data);

            // Updating the DOM elements with weather information
            document.querySelector(".city-country-name").innerHTML = data[0].city_name;
            document.querySelector(".current-temp").innerHTML = Math.round(data[0].temperature) + "°C";
            document.querySelector(".humidity-info").innerHTML = data[0].Humidity + "%";
            document.querySelector(".wind-info").innerHTML = data[0].Wind_speed + " m/s";
            document.querySelector(".pressure-info").innerHTML = data[0].Pressure + "mb";

            // Formatting the local time for display
            const rawDate = new Date();
            const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'};
            const formattedDate = rawDate.toLocaleDateString('en-US', options);
            document.querySelector(".current-date").innerHTML = formattedDate;

            // Assigning the weather condition based on the data.weather_condition
            if (data[0].weather_condition.length > 0) {
                const weatherMain = data[0].weather_condition;
                document.querySelector(".weather-condition").innerHTML = `${weatherMain}`;

                // Mapping weather conditions to icons
                const weatherIcons = {
                    "overcast clouds": "https://cdn-icons-png.flaticon.com/128/1146/1146869.png",
                    "clear sky": "https://cdn-icons-png.flaticon.com/256/6974/6974833.png",
                    "scattered clouds": "https://cdn-icons-png.flaticon.com/128/1959/1959321.png",
                    "broken clouds": "https://cdn-icons-png.flaticon.com/128/414/414927.png",
                    "haze": "https://cdn-icons-png.flaticon.com/128/2930/2930095.png",
                    "rain": "https://cdn-icons-png.flaticon.com/128/4088/4088981.png"
                };

                // Setting the weather icon based on the condition
                weatherIcon.src = weatherIcons[weatherMain];
            }
        }, 1000);
    } catch (error) {
        // Handling errors - you can customize this part based on your needs
        console.error("Error:", error.message);
        // You may want to update the DOM to show an error message to the user
        // For example: document.querySelector(".error-message").innerHTML = "An error occurred. Please try again.";
    }
}

// Function to handle keypress events in the search input
function handleKeyPress(event) {
    if (event.key === "Enter") {
        selectedCity = searchbox.value;
        checkWeather(selectedCity);
    }
}

// Adding keypress event listener to the search input
searchbox.addEventListener("keypress", handleKeyPress);

// Adding click event listener to the search button
searchbtn.addEventListener("click", () => {
    selectedCity = searchbox.value;
    checkWeather(selectedCity);
});

// Setting a default city (Udupi) and checking its weather on page load
selectedCity = "Udupi";
checkWeather(selectedCity);
