# Endpoint


### Rejestracja
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/register | email:string, password:string, username:string |

### Headers
| name_headers | value            |
|--------------|------------------|
| Content-Type | application/json |

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
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |

### status code
| status | description                    |
|--------|--------------------------------|
| 201    | utworzone użytkownika          |
| 401    | Nie poprawna walidacja inputów |
| 409    | Wyrzuciło serwer               |

|----------------------------------------------------------------------------------------------------------|
### Logowanie
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/login | email:string, password:string, remember_me:boolean |
### Headers
| name_headers | value            |
|--------------|------------------|
| Content-Type | application/json |
### Walidacja inputów z strony serwera
| validation | description                         | belongs                    |
|------------|-------------------------------------|----------------------------|
| required   | nie może być puste                  | email, password, remember_me  |
| email      | musi zgadzac się regex              | email                      |
| exists     | musi znajdować się w bazie          | email                      |
### Serwer zwraca response:
| response_json  | description                                         |
|----------------|-----------------------------------------------------|
| status         | zwróci Ci 'error' albo 'success'                    |
| message        | zwróci Ci informacje na temat error albo success    |
| server_message | Zwróci Ci tylko ten komunikat, jeśli wyrzuci serwer |
### status code
| status | description                    |
|--------|--------------------------------|
| 200    | zalogowano          |
| 401    | Nie poprawna walidacja inputów |
| 409    | Wyrzuciło serwer               |
