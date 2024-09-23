# fruits-and-vegetables-challenge
This service will take a request.json file and create two separate collections for Fruits and Vegetables.

## Installation
- Clone this repository
- Run `composer install`
- Open .env file and setup your database connection adding the following line and adding your DB credentials:
  - `DATABASE_URL="mysql://{YOUR USER}:{YOUR PASSWORD}@127.0.0.1:3306/{DATABASE NAME}?serverVersion=8&charset=utf8mb4"`
- In the terminal Run `php bin/console doctrine:database:create` to create the configured database
- Run `php bin/console doctrine:migrations:migrate` to create database tables for this project.
- Run `symfony serve`

## Endpoints


### 1. Process Json

`method: GET`

`/api/process-json`

Process request.json file and create two separate collections for Fruits and Vegetables

### 2. Get a list of Fruits

`method: GET`

`/api/fruits`

Return a list of Fruits

#### Filters
- name
- quantity
- unit

#### Example

`/api/fruits?name=kiwi&quantity=10000`

### 3. Add a Fruit

`method: POST`

`/api/fruits`

Return the created resource

#### payload
```
{  
  "name": "string",
  "quantity": int,
  "unit": "string"
}
```

### 4. Get one Fruit

`method: GET`

`/api/fruit/{id}`

#### Params
- id [string] - Required

#### Example

`/api/fruit/18`

### 5. Delete one Fruit

`method: DELETE`

`/api/fruit/{id}`

#### Params
- id [string] - Required

#### Example

`/api/fruit/18`

### 6. Advanced Search of Fruits

`method: GET`

`/api/fruit/search`

Return a list of Fruits

#### Params
- term: name of the fruit you are looking for (LIKE search)

#### Example

`/api/fruit/search?term=appl`


### 7. Get a list of Vegetables

`method: GET`

`/api/vegetables`

Return a list of Fruits

#### Filters
- name
- quantity
- unit

#### Example

`/api/vegetables?name=onion&quantity=3000`

### 8. Add a Vegetable

`method: POST`

`/api/vegetables`

Return the created resource

#### payload
```
{  
  "name": "string",
  "quantity": int,
  "unit": "string"
}
```

### 9. Get one Vegetable

`method: GET`

`/api/vegetable/{id}`

#### Params
- id [string] - Required

#### Example

`/api/vegetable/18`

### 10. Delete one Vegetable

`method: DELETE`

`/api/vegetable/{id}`

#### Params
- id [string] - Required

#### Example

`/api/vegetable/18`

### 11. Advanced Search of Vegetables

`method: GET`

`/api/vegetable/search`

Return a list of Vegetables

#### Params
- term: name of the vegetable you are looking for (LIKE search)

#### Example

`/api/vegetable/search?term=appl`


## Testing
- In the terminal Run `php bin/phpunit`.

