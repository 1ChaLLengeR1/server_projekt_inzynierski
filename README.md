# Twórcy aplikacji
### Użyte technologie:
- React.js
- Laravel 10 + JWT
- PostgreSQL

### UI/UX Designer: Maks Kowalski
- FB: https://www.facebook.com/superchlopak07

### Frontend: Wiktor Szot
- link github: https://github.com/Szocik01

### Backend: Artur Ścibor, czyli ja :)
- więcej ode mnie: https://arturscibor.pl/projects/project/70a13d41-6d17-4dcf-b085-c6eb700febb7
### Strona:
- link: https://projektinzynierski.strona.arturscibor.pl/

_________________________________________________________________________________________________________________________________________________________
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
### Get_all_quizzes
| method | url                                                | 
|--------|----------------------------------------------------|
| get    | /api/routers/http/controllers/quiz/get_all_quizzes |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
### Serwer zwraca response:
| response_json  | description                                        |
|----------------|----------------------------------------------------|
| array          | Tablica quizów  wszystkich                         |
### status code
| status | description                                       |
|--------|---------------------------------------------------|
| 200    | tablica quizów  wszystkich                        |
| 500    | Wyrzuciło serwer                                  |
_________________________________________________________________________________________________________________________________________________________
### Get_single_quiz
### Przyjmuje obiekt o strukturze:
| method | url                                                | body                |
|--------|----------------------------------------------------| --------------------|
| post   | /api/routers/http/controllers/quiz/get_single_quiz | user_id, quiz_id    |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | user_id, quiz_id                          |
| exists     | id musi zgadząć się w bazie                                                     | quiz_id                                   |
| uuid       | id musi być poprawnie zapisane (nie zmieniaj id, które jest pobrane z serwera!) | quiz_id                                   |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| obecjt         | Zwraca obiekt single quizu                          |
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                       |
|--------|---------------------------------------------------|
| 200    | obiekt single quizu                               |
| 400    | błąd walidacji inputu któregoś                    |
| 500    | Wyrzuciło serwer                                  |


_________________________________________________________________________________________________________________________________________________________
### Get_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         | body             |
|--------|---------------------------------------------| -----------------|
| post   | /api/routers/http/controllers/quiz/get_quiz | user_id          |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                         | belongs                             |
|------------|-------------------------------------|-------------------------------------|
| required   | nie może być puste                  | user_id                             |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| array          | Tablica quizów  stworzonych przez użytkownika       |
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                       |
|--------|---------------------------------------------------|
| 200    | tablica quizów stworzonych przez użytkownika      |
| 500    | Wyrzuciło serwer                                  |
_________________________________________________________________________________________________________________________________________________________
### Add_Quiz
### Przyjmuje obiekt o strukturze:
| method | url                                         | Form_Data                           |
|--------|---------------------------------------------|-------------------------------------|
| post   | /api/routers/http/controllers/quiz/add_quiz | name, description, image, quantity  |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                         | belongs                                       |
|------------|-------------------------------------|-----------------------------------------------|
| required   | nie może być puste                  | user_id, name, description, image, quantity   |
| min:10     | minimalna długość                   | name                                          |
| min:20     | minimalna długość                   | description                                   |
| max:40     | maksymalna długość                  | name                                          |
| max:400    | maksymalna długość                  | description                                   |
| max:400    | maksymalna długość                  | description                                   |
| integer    | musi być zapisane jako integer      | quantity                                      |
| mimes      | rozszerzenie jpeg, jpg, pmg         | image                                         |
| size       | waga zdjęcia od 0 do 5M             | image                                         |
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
| required   | nie może być puste                                                              | id, user_id, name, description, quantity  |
| exists     | id musi zgadząć się w bazie                                                     | id                                        |
| uuid       | id musi być poprawnie zapisane (nie zmieniaj id, które jest pobrane z serwera!) | id                                        |
| min:10     | minimalna długość                                                               | name                                      |
| min:20     | minimalna długość                                                               | description                               |
| max:40     | maksymalna długość                                                              | name                                      |
| max:400    | maksymalna długość                                                              | description                               |
| integer    | musi być zapisane jako integer                                                  | quantity                                  |
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
| delete | /api/routers/http/controllers/quiz/delete_quiz | id                                       |
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
_________________________________________________________________________________________________________________________________________________________
## Requesty do Typów zadań
### Get_Type
### Przyjmuje obiekt o strukturze:
| method | url                                                   |
|--------|-------------------------------------------------------|
| get    | /api/routers/http/controllers/type_question/get_types |
### Headers
| name_headers | value            |
|--------------|------------------|
|  Accept      | application/json |
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
| 200    | zwróci tablice typów           |
| 500    | Wyrzuciło serwer               |
_________________________________________________________________________________________________________________________________________________________
### Add_type
### Przyjmuje obiekt o strukturze:
| method | url                                                  | Body                                     |
|--------|------------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/type_question/add_type | name, description, type                  |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | name, description, type                   |
| min: 6     | pole musi miec minimum 6 znaków                                                 | name                                      |
| min: 10    | pole musi miec minimum 10 znaków                                                | description                               |
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
| 201    | poprawnie utworzono                                                                                                        |
| 400    | zwróci Ci informacje, która walidacja jest nie poprawna                                                                    |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Edit_type
### Przyjmuje obiekt o strukturze:
| method | url                                          | Body                                     |
|--------|----------------------------------------------|------------------------------------------|
| put    | /api/routers/http/controllers/quiz/edit_quiz | id, name, description, type              |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | id, name, description, type               |
| exists     | id musi zgadząć się w bazie                                                     | id                                        |
| uuid       | id musi być poprawnie zapisane (nie zmieniaj id, które jest pobrane z serwera!) | id                                        |
| min:5      | minimalna długość                                                               | name                                      |
| min:10     | minimalna długość                                                               | description                               |
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
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Delete_type
### Przyjmuje obiekt o strukturze:
| method | url                                                     | Body                                     |
|--------|---------------------------------------------------------|------------------------------------------|
| delete | /api/routers/http/controllers/type_question/delete_type | id                                       |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | id                                        |
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
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
## Requesty do Pytań
### Get_Single_Question
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                     |
|--------|------------------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/question/get_single_question | id                                       |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | id                                        |
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
| 200    | poprawnie zwróciło obiekt                                                                                                  |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Get_Type_Question
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                     |
|--------|------------------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/question/get_type_question   | id                                       |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | id                                        |
| exists     | typ musi zgadząć się w bazie                                                    | id                                        |
| uuid       | uuid musi być poprawnie zapisane                                                | id                                        |
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
| 200    | poprawnie zwróciło obiekt type question                                                                                                  |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 500    | Wyrzuciło serwer                                                                                                           |

_________________________________________________________________________________________________________________________________________________________
### Get_Questions
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                     |
|--------|------------------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/question/get_all_questions   | type                                     |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | type                                      |
| exists     | typ musi zgadząć się w bazie                                                    | type                                      |
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
| 200    | poprawnie zwróciło tablice obiektów                                                                                        |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Only_Questions
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                     |
|--------|------------------------------------------------------------|------------------------------------------|
| post   | /api/routers/http/controllers/question/only_questions      | quiz_id, user_id                         |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | quiz_id, user_id                          |
| uuid       | uuid musi być porawnie zapisane                                                 | quiz_id, user_id                          |
| exists     | id musi zgadząć się w bazie                                                     | quiz_id, user_id                          |
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
| 200    | zwróci tablice question                                                                                                    |
| 400    | zwróci Ci informacje, która walidacja jest nie poprawna z pytania                                                          |
| 401    | user_id jest błędne z id w tokenie                                                                                         |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Add_Question
### Przyjmuje obiekt o strukturze:
## Zdjęcia nie są wymagane!
| method | url                                                  | Body                                                                              |
|--------|------------------------------------------------------|-----------------------------------------------------------------------------------|
| post   | /api/routers/http/controllers/question/add_questions | quiz_id, type_id, user_id, text, image, array_answers[index, text, answer_type], array_answers_image[tablica plików]  |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | quiz_id, type_id, user_id, text,          |
| exists     | id musi zgadząć się w bazie                                                     | quiz_id, type_id                          |
| uuid       | uuid musi być porawnie zapisane                                                 | quiz_id, type_id                          |
| min:10     | minimalna długość znaków to 10                                                  | text                                      |
| mimes      | rozszerzenia zdjęć jpeg,png,jpg                                                 | image                                     |
| between    | waga zdjęcia od 0 do 5M                                                         | image                                     |

### Walidacja tablicy array_answers
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | index , answer_type,                      |
| boolean    | Musisz podać albo 1(true) albo 0(false). Nie mogą być to wartości True, False   | answer_type                               |

### Walidacja tablicy z zdjęciami
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| mimes      | rozszerzenia zdjęć jpeg,png,jpg                                                 | array_answers_image                       |
| between    | waga zdjęcia od 0 do 5M                                                         | array_answers_image                       |

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
| 201    | poprawnie dodano pytanie                                                                                                   |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna z pytania                                                           |
| 401    | błąd w tablicy z pytaniami                                                                                                 |
| 402    | błąd w tablicy z zdjęciami do pytań                                                                                        |
| 403    | user_id i id w tokenie są nie porawne                                                                                      |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Edit_Question
### Przyjmuje obiekt o strukturze:
## Zdjęcia nie są wymagane!
| method | url                                                  | Body                                                                              |
|--------|------------------------------------------------------|-----------------------------------------------------------------------------------|
| post   | /api/routers/http/controllers/question/edit_question | question_id, quiz_id, type_id, user_id, text, image, array_answers[index, text, answer_type], array_images[tablica plików], delete_answers[tablica id_answear], array_answers_edit[index, text, answer_type, delete_image]   |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                                   |
|------------|---------------------------------------------------------------------------------|-----------------------------------------------------------|
| required   | nie może być puste                                                | question_id, quiz_id, type_id, user_id, text, array_answers_edit[index, answer_type, delete_image], array_answers[index, answer_type]              |
| uuid       | uuid musi być porawnie zapisane                                   | question_id, quiz_id, type_id, array_answers_edit[index]  |
| min:10     | musi text mieć minimum 10 znaków                                  | text                                                      |
| exists     | id musi zgadząć się w bazie                                       | question_id, quiz_id, type_id, array_answers_edit[index]  |
| mimes      | rozszerzenia zdjęć jpeg,png,jpg                                   | array_images, image                                       |
| between    | waga zdjęcia od 0 do 5M                                           | array_images, image                                       |
| boolean    | musi być zapisane jako 1 lub 0 (nie może być jako true i false)   | array_answers_edit[answer_type, delete_image], array_answers[answer_type]  |
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
| 401    | user_id i id w tokenie są nie porawne                                                                                      |
| 402    | błąd walidacji tablicy do usunięcia!                                                                                       |
| 403    | błąd walidacji tablicy zdjęć!                                                                                              |
| 404    | błąd walidacji tablicy z pytaniami do dodania                                                                              |
| 405    | błąd walidacji tablicy z pytaniami do edycji                                                                               |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Delete_question
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                                                              |
|--------|------------------------------------------------------------|-----------------------------------------------------------------------------------|
| delete | /api/routers/http/controllers/question/delete_question     | id, user_id                                                                       |
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
| 401    | user_id i id w tokenie są nie porawne                                                                                      |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Get_Game
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                                                              |
|--------|------------------------------------------------------------|-----------------------------------------------------------------------------------|
| post   | /api/routers/http/controllers/game/get_game                | quiz_id                                                                           |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | user_id                                   |
| exists     | id musi zgadząć się w bazie                                                     | user_id                                   |
| uuid       | id musi być poprawnie zapisane                                                  | user_id                                   |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status_code    | zwróci kod statusu                                  |
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                                                                                                                                   |
|--------|-----------------------------------------------------------------------------------------------------------------------------------------------|
| 200    | pobierze obiekt gry, jeśli quantity w quizie do gry wynosi 0 to pobierze wszystkie pytania do tej gry. Jeśli 10 quantity to pobierze tylko 10 |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                                        |
| 500    | Wyrzuciło serwer                                                                                                                              |
_________________________________________________________________________________________________________________________________________________________
### Get_Result
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                                                              |
|--------|------------------------------------------------------------|-----------------------------------------------------------------------------------|
| post   | /api/routers/http/controllers/game/get_result              | quiz_id, limit                                                                    |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | quiz_id                                   |
| exists     | id musi zgadząć się w bazie                                                     | quiz_id                                   |
| uuid       | id musi być poprawnie zapisane                                                  | quiz_id                                   |
| limit      | pusty string to pobierze wszystkie, liczba to ilość pobranych wyników           | limit                                     |
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
| 200    | pobierze tablice wyników                                                                                                   |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 500    | Wyrzuciło serwer                                                                                                           |
_________________________________________________________________________________________________________________________________________________________
### Add_Edit_Result
### Przyjmuje obiekt o strukturze:
| method | url                                                        | Body                                                                              |
|--------|------------------------------------------------------------|-----------------------------------------------------------------------------------|
| post   | /api/routers/http/controllers/game/add_edit_result         | user_id, quiz_id, result                                                          |
### Headers
| name_headers   | value            |
|----------------|------------------|
|  Accept        | application/json |
| authorization  | Bearer 'tu token'|
### Walidacja inputów z strony serwera
| validation | description                                                                     | belongs                                   |
|------------|---------------------------------------------------------------------------------|-------------------------------------------|
| required   | nie może być puste                                                              | quiz_id, user_id, result                  |
| exists     | id musi zgadząć się w bazie                                                     | quiz_id, user_id                          |
| uuid       | id musi być poprawnie zapisane                                                  | quiz_id, user_id                          |
| numeric    | musi być podana liczbya całkowita                                               | result                                    |
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
| 200    | poprawnie zapisze zmiany w result dla użytkownika                                                                          |
| 201    | utworzy Ci nowego użytkownika w tabeli result                                                                              |
| 400    | zwróci Ci informacje, któa walidacja jest nie poprawna                                                                     |
| 401    | user_id i id w tokenie są nie porawne                                                                                      |
| 500    | Wyrzuciło serwer                                                                                                           |

