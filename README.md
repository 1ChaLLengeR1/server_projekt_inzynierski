# Endpoint


### Rejestracja
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/login | email:string, password:string, remember_me:boolean |

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


