# Endpoint


### Rejestracja
### Przyjmuje obiekt o strukturze:
| method | url                                      | body                        |
|--------|------------------------------------------|-----------------------------|
| post   | /api/routers/http/controllers/auth/login | email:string, password:string, remember_me:boolean |

### Walidacja inputów
| validation | description                         | belongs                    |
|------------|-------------------------------------|----------------------------|
| required   | nie może być puste                  | username, email, password  |
| min-6      | minimalna długość                   | username                   |
| min:8      | minimalna długość                   | password                   |
| max:12     | maksymalna długość                  | username                   |
| email      | musi zgadzac się regex              | email                      |
| unique     | nie może być takiego adresu w bazie | email                      |



