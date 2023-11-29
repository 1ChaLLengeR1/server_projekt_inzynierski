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

## Ważne - Serwer przyjmuje maksylamnie plik o wartości 10M. Jeśli wrzucisz coś więcej to Ci wypluje błąd ogólny 400 z informacją, że jest błąd w trakcie uploadu zdjęcia!
### Get_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         | body             |
|--------|---------------------------------------------| -----------------|
| get    | /api/routers/http/controllers/quiz/get_quiz | user_id          |
### Headers
| name_headers | value            |
|--------------|------------------|
|  Accept      | application/json |
### Walidacja inputów z strony serwera
| validation | description                         | belongs                             |
|------------|-------------------------------------|-------------------------------------|
| required   | nie może być puste                  | user_id                             |
### Serwer zwraca response:
| response_json  | description                                        |
|----------------|----------------------------------------------------|
| array          | Tablica quizów  stworzonych przez użytkownika      |
### status code
| status | description                                       |
|--------|---------------------------------------------------|
| 200    | tablica quizów stworzonych przez użytkownika      |
| 500    | Wyrzuciło serwer                                  |
_________________________________________________________________________________________________________________________________________________________
### Add_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         | Form_Data                       |
|--------|---------------------------------------------|---------------------------------|
| post   | /api/routers/http/controllers/quiz/add_quiz | name, description, image        |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                         | belongs                             |
|------------|-------------------------------------|-------------------------------------|
| required   | nie może być puste                  | user_id, name, description, image   |
| min:10     | minimalna długość                   | name                                |
| min:20     | minimalna długość                   | description                         |
| max:40     | maksymalna długość                  | name                                |
| max:400    | maksymalna długość                  | description                         |
| mimes      | rozszerzenie jpeg, jpg, pmg         | image                               |
| size       | waga zdjęcia od 0 do 5M             | image                               |
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
_________________________________________________________________________________________________________________________________________________________
### Edit_quiz
### Przyjmuje obiekt o strukturze:
| method | url                                          | Form_Data                                |
|--------|----------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/quiz/edit_quiz | user_id, name, description, image(NIE WYMAGANE)   |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | user_id, name, description, id            |
| exists     | id musi zgadząć się w bazie                                                     | id                                        |
| uuid       | id musi być poprawnie zapisane (nie zmieniaj id, które jest pobrane z serwera!) | id                                        |
| min:10     | minimalna długość                                                               | name                                      |
| min:20     | minimalna długość                                                               | description                               |
| max:40     | maksymalna długość                                                              | name                                      |
| max:400    | maksymalna długość                                                              | description                               |
| mimes      | rozszerzenie jpeg, jpg, pmg                                                     | image                                     |
| size       | waga zdjęcia od 0 do 5M                                                         | image                                     |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                                                                                                |
|--------|----------------------------------------------------------------------------------------------------------------------------|
| 200    | poprawnie z modyfikowano                                                                                                   |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 401    | zwróci Ci informacje, jeśli napotka błąd podczas usuwania bierzącego zdjęcia z serwera i podmiany, które wysyłasz          |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Delete_quiz
### Przyjmuje obiekt o strukturze:
| method | url                                            | Body                                     |
|--------|------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/quiz/delete_quiz | id                                       |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | id, user_id                               |
| exists     | id musi zgadząć się w bazie                                                     | id                                        |
| uuid       | id musi być poprawnie zapisane (nie zmieniaj id, które jest pobrane z serwera!) | id                                        |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                                                                                                |
|--------|----------------------------------------------------------------------------------------------------------------------------|
| 200    | poprawnie usunięto                                                                                                         |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 401    | zwróci Ci informacje, jeśli napotka błąd podczas usuwania bierzącego zdjęcia z serwera                                     |
| 500    | Wyrzuciło serwer                                                                                                           |
