# Weather Sample APP
Sample APP showing the use of API for forecast.

## Description:
User will be able to provide his city and country via form 
and after submission system will display current weather forecast. 
System will be using several API providers to generate AVG value for city provided.

## Weather API used
1. https://openweathermap.org/API (a must)
2. https://www.climacell.co/
3. https://stormglass.io

## Requirements:
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
6. Build database `docker exec -it weahter_php php bin/console doctrine:schema:update`
7. Enter `127.0.0.1` in browser (or other host pointing to localhost)

## Stack:
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

## TODO:
- make unit for existing

- Api request to endpoints
- save request to DB
- check if in db for saving
- add logger to check if success
- return medium or 2 more predictable

- add redis
- save value to redis when getting from db
- check if redis has the value already

- add routes for front (start, search result)
- symfony form for search input - lat + lon (no class) 
- add radio button with what to search for: temp or humidity
- bootstrap 5 with bg image
- get sample data for setup location
- prepare show data
- describe this is only demo and no named cities are searched beacuse only one API works with cities
- limit lat and long to xx.x value (in front and back)

- elasticsearch to get db content to search 
    (add sample cities in db with lat and long) - fixtures...

- rabbitmq to make one search per 20 sec or 10 sec
- websocket to wait for result?

