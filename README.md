# Article Test Project | Symfony 6 + PHP 8.2 with Docker

## Install

- install docker & docker-compose
- install project with following commands:
```bash
  docker-compose build
  docker-compose up
  docker-compose exec app composer install
  docker-compose exec app bin/console d:m:m
```
- Go to homepage: http://127.0.0.1:9000/
- Go to phpmyadmin: http://127.0.0.1:8081/

## Utilisation

### Create a user

To execute request on Apis you have to create your user on the app.
To do that you can insert a new row in User. But it's better to use this symfony command:
```shell
docker-compose exec app bin/console app:create-user {firstname} {lastname}
```
ex:
```shell
docker-compose exec app bin/console app:create-user admin admin
```
The command will create a new user and generate an api key for you. Please make a note of it so that you can use it to authenticate yourself.

### Authentication

To authenticate yourself, add in your header a `X-AUTH-TOKEN` with your apiKey in value.
In a curl request it should look like this:

```shell
curl --request DELETE \
--url http://127.0.0.1:9000/api/v1/editorial/article/1 \
--header 'Content-Type: application/json' \
--header 'X-AUTH-TOKEN: {apiKey}'
```

Also add the `Content-Type: application/json` header to post your payloads in json format.

### Apis with examples of payloads

- Write an Article:
    ```shell
    curl --request POST \
    --url http://127.0.0.1:9000/api/v1/editorial/article \
    --header 'Content-Type: application/json' \
    --header 'X-AUTH-TOKEN: {apiKey}' \
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
  --url http://127.0.0.1:9000/api/v1/editorial/article/1 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{
     "title": "Un nouvel espoir ou désespoir?",
     "content": "Ce film a permis de faire connaître au monde R2D2 et C3PO"
  }'  
  ```
- Publish an Article:
    ```shell
    curl --request POST \
    --url http://127.0.0.1:9000/api/v1/editorial/article/1/publish \
    --header 'Content-Type: application/json' \
    --header 'X-AUTH-TOKEN: {apiKey}' \
    ```
- Convert to draft an Article:
  ```shell
  curl --request POST \
  --url http://127.0.0.1:9000/api/v1/editorial/article/1/convert-to-draft \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{
        "releaseDate": "2023-03-26 20:00:00"
  }'
  ```
- Delete an Article:
  ```shell
  curl --request DELETE \
  --url http://127.0.0.1:9000/api/v1/editorial/article/1\
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  ```
- List Article:
  ```shell
  curl --request GET \
  --url http://127.0.0.1:9000/api/v1/editorial/article \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{
	"status": ["draft", "published"],
	"page": 1,
	"itemPerPage": 4
  }'
  ```
- Get an Article:
  ```shell
  curl --request GET \
  --url http://127.0.0.1:9000/api/v1/editorial/article/1 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}'
  ```

- Create Tag:
  ```shell
  curl --request POST \
  --url http://127.0.0.1:9000/api/v1/editorial/tag \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{
        "name": "sf"
  }'
  ```
  
- Tag Article
  ```shell
  curl --request POST \
  --url http://127.0.0.1:9000/api/v1/editorial/tag/3/article/7 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{}'
  ```

- UnTag Article
  ```shell
  curl --request DELETE \
  --url http://127.0.0.1:9000/api/v1/editorial/tag/3/article/7 \
  --header 'Content-Type: application/json' \
  --header 'X-AUTH-TOKEN: {apiKey}' \
  --data '{}'
  ```  