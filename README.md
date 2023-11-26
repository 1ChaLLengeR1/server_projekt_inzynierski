# Endpoint


### Rejestracja
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/register | email:string, password:string, username:string |

### Headers
| name_headers | value            |
|--------------|------------------|
| Accept       | application/json |

### Walidacja inputów z strony serwera
| validation | description                         | belongs                    |
|------------|-------------------------------------|----------------------------|
| required   | nie może być puste                  | username, email, password  |
| min-6      | minimalna długość                   | username                   |
| min:8      | minimalna długość                   | password                   |
| max:12     | maksymalna długość                  | username                   |
| email      | musi zgadzac się regex              | email                      |
| unique     | nie może być takiego adresu w bazie | email                      |

### Serwer zwraca response:
| response_json       | description                                         |
|---------------------|-----------------------------------------------------|
| status_code         | zwróci kod statusu                                  |
| status              | zwróci Ci 'error' albo 'success'                    |
| message             | zwróci Ci informacje na temat error albo success    |
| server_message      | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |

### status code
| status | description                      |
|--------|----------------------------------|
| 201    | utworzone użytkownika            |
| 401    | Nie poprawna walidacja inputów   |
| 406    | Email musi być unikatowy w bazie |
| 500    | Wyrzuciło serwer                 |

_________________________________________________________________________________________________________________________________________________________
### Logowanie
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/login | email:string, password:string, remember_me:boolean |
### Headers
| name_headers | value            |
|--------------|------------------|
| Accept       | application/json |
### Walidacja inputów z strony serwera
| validation | description                         | belongs                       |
|------------|-------------------------------------|-------------------------------|
| required   | nie może być puste                  | email, password, remember_me  |
| email      | musi zgadzac się regex              | email                         |
| exists     | musi znajdować się w bazie          | email                         |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                    |
|--------|--------------------------------|
| 200    | zalogowano          |
| 401    | Nie poprawna walidacja inputów |
| 500    | Wyrzuciło serwer               |
_________________________________________________________________________________________________________________________________________________________
### RefreshToken
### Przyjmuje obiekt o strukturze:
| method | url                                      |
|--------|------------------------------------------|
| get    | api/routers/http/controllers/auth/refresh_token |
### Headers
| name_headers | value            |
|--------------|------------------|
|  Accept      | application/json |
| authorization  | Bearer 'tu token'|
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                    |
|--------|--------------------------------|
| 200    | poprawnie                      |
| 401    | Nie poprawne ID                |
| 500    | Wyrzuciło serwer               |
_________________________________________________________________________________________________________________________________________________________
### LogoutToken
### Przyjmuje obiekt o strukturze:
| method | url                                      |
|--------|------------------------------------------|
| get    | api/routers/http/controllers/auth/logout |
### Headers
| name_headers | value            |
|--------------|------------------|
|  Accept      | application/json |
| authorization  | Bearer 'tu token'|
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                    |
|--------|--------------------------------|
| 200    | poprawnie                      |
| 401    | Nie poprawne ID                |
| 500    | Wyrzuciło serwer               |
_________________________________________________________________________________________________________________________________________________________
### Get_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         |
|--------|---------------------------------------------|
| get    | /api/routers/http/controllers/quiz/get_quiz |
### Headers
| name_headers | value            |
|--------------|------------------|
|  Accept      | application/json |
### Serwer zwraca response:
| response_json  | description                               |
|----------------|-------------------------------------------|
| array          | Tablica quizów                            |
### status code
| status | description                          |
|--------|--------------------------------------|
| 200    | tablica quizów                       |
| 500    | Wyrzuciło serwer                     |
_________________________________________________________________________________________________________________________________________________________
### Add_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         |
|--------|---------------------------------------------|
| post   | /api/routers/http/controllers/quiz/add_quiz |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                         | belongs                    |
|------------|-------------------------------------|----------------------------|
| required   | nie może być puste                  | name, description, image   |
| min:10     | minimalna długość                   | name                       |
| min:20     | minimalna długość                   | description                |
| max:40     | maksymalna długość                  | name                       |
| max:400    | maksymalna długość                  | description                |
| mimes      | rozszerzenie jpeg, jpg, pmg         | image                      |
| size       | waga zdjęcia od 0 do 5M             | image                      |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| id_quiz        | zwróci Ci id quizu, który stworzyłeś                |
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                                      |
|--------|------------------------------------------------------------------|
| 201    | utworzono quiz                                                   |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna           |
| 500    | Wyrzuciło serwer                                                 |
