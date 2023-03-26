# Article Test CR Project

[Exercise statement](EXAM.md)

## Install

- Create your .env with .env.dist template and replace with your db configuration.
- Launch `composer install` command to get vendor
- Use `php bin/console doctrine:migration:migrate` or execute [MySQL request](migrations/Version20230325095529.php) to generate tables.
- Start your symfony web server with `symfony server:start`

## Utilisation

### Run Unit Test
To run test execute this command:
```shell
php bin/phpunit
```

### Create a user

To execute request on Apis you have to create your user on the app.
To do that you can insert a new row in User. But it's better to use this symfony command:
```shell
php bin/console app:create-user {firstname} {lastname}
```
The command will create a new user and generate an api key for you. Please make a note of it so that you can use it to authenticate yourself.

### Authentication

To authenticate yourself, add in your header a `X-AUTH-TOKEN` with your apiKey in value.
In a curl request it should look like this:

```shell
curl --request DELETE \
--url http://127.0.0.1:8000/editorial/article/1 \
--header 'Content-Type: application/json' \
--header 'X-AUTH-TOKEN: {apiKey}'
```

Also add the `Content-Type: application/json` header to post your payloads in json format.

### Apis with examples of payloads

- Write an Article:
    ```shell
    curl --request POST \
    --url http://127.0.0.1:8000/editorial/article \
    --header 'Content-Type: application/json' \
    --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
    --data '{
       "title": "Un nouvel espoir",
       "content": "Ce film a permis de faire connaître au monde R2D2",
       "releaseDate": null,
       "status": "draft"
    }'  
    ```
- Edit an Article:
  ```shell
  curl --request PUT \
  --url http://127.0.0.1:8000/editorial/article/1 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
  --data '{
     "title": "Un nouvel espoir ou désespoir?",
     "content": "Ce film a permis de faire connaître au monde R2D2 et C3PO"
  }'  
  ```
- Publish an Article:
    ```shell
    curl --request POST \
    --url http://127.0.0.1:8000/editorial/article/1/publish \
    --header 'Content-Type: application/json' \
    --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
    ```
- Convert to draft an Article:
  ```shell
  curl --request POST \
  --url http://127.0.0.1:8000/editorial/article/1/convert-to-draft \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
  --data '{
        "releaseDate": "2023-03-26 20:00:00"
  }'
  ```
- Delete an Article:
  ```shell
  curl --request DELETE \
  --url http://127.0.0.1:8000/editorial/article/1\
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
  ```
- List Article:
  ```shell
  curl --request GET \
  --url http://127.0.0.1:8000/editorial/article \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
  --data '{
	"status": ["draft", "published"],
	"page": 1,
	"itemPerPage": 4
  }'
  ```
- Get an Article:
  ```shell
  curl --request GET \
  --url http://127.0.0.1:8000/editorial/article/1 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: a9125212275c104105760bf8cff0dd4babd9ff03c6c2e28623db74909e803f60' \
  ```