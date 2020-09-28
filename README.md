# Weather Sample APP
Sample APP showing the use of API for forecast.

## Description:
User will be able to provide his city and country via form 
and after submission system will display current weather forecast. 
System will be using several API providers to generate AVG value for city provided.

#### Problems:
1. Only one API provide search by location name so main search is based on coords

### Weather API used
1. https://openweathermap.org/API (a must)
2. https://www.climacell.co/
3. https://stormglass.io

### Requirements:
- All results kept in DB for location
- Caching mechanism for ease of app use
- Error handling (exceptions) / Data separation
- DDD structure
- Ability to easily add new data sources (with interfaces / abstract class)

## Running the APP
1. Install docker
2. Clone project 
3. Copy .env file to .env.local and enter APIs key for each API service
4. (Optional) Replace port 80 if is already in use locally
5. Run app with `docker-compose up -d`
6. Build database `docker exec -it weahter_php php bin/console doctrine:schema:update --force`
    or use migrations `docker exec -it weahter_php doctrine:migrations:migrate`
7. Enter `127.0.0.1` in browser (or other host pointing to localhost)

### Stack:
- PHP 7.4
- Docker
- Symfony 5
- PostgreSQL 12
- Redis
- RabbitMQ
- ElasticSearch
- JS ES6+ (with typescript) + React (with typescript)
- Bootstrap 5 (alpha)
- HTML5 / CSS3

### More to work on:
- Update front to use API request and show on page
- RabbitMQ can be added to queue all API calls (preventing API block - limit request)
- Add React components to make it look more dynamic
- Add more tests
- Make more complex algorithm for adding measurements
- Make trait log into DB
- Filter request by date (for test purpose more data is better)
