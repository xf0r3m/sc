# SC - SiteCatalog

__SiteCatalog__ - jest sposobem na przechowywanie odnośników do stron (katalog stron), dostępnych na każdym urządzeniu. Za pomocą przeglądarki sieci web. W pełni napisany w PHP. Dla designu oraz funkcjonowania, nie których elementów wykorzystano bibliotekę Bootstrap 4. Aplikacja oparta jest o przechowywanie danych w formacie JSON.

__Wymagania:__

* LAMP (Linux Apache MySQL(nieużywany) PHP)

__Instalacja (czynności wykonujemy jako root):__

1. Instalujemy LAMP stack.
2. cd _katalog serwera www_
3. git clone https://github.com/xf0r3m/sc
4. cd sc
5. mv * ..
6. chown -R www-data:www-data _katalog serwera www_
7. chmod -R 775 _katalog serwera www_
8. Przechodzimy pod adres: _adres sc/admin_
9. Dokonujemy rejestracji, wpisując login i hasło
10. Po dokonanej rejestracji, zostaniemy automatycznie zalogowani. Teraz możemy przejść do zmiany ustawień lub dodania jakiś sieci.

__Ustawienia:__

1. Tytułem katalogu jest tekst, wyświetlany w nagłówku na tle obrazka nagłówkowego.
2. Obrazek nagłówkowy, jest to dla nagłówka wyświetane pod tytułem katalogu.
3. Kolor tła, tutaj ustawiamy kolor tła dla całej witryny.

__Zarządzanie zakładkami:__

Po zalogowaniu, automatycznie pokażą nam się ustawienia. Aby przejść do konfigurowania katalogu klikamy odnośnik _Przejdź do katalogu_
pod nagłówkiem, mamy zielony przycisk _Dodaj strone_, po jego kliknięciu pojawi sie okienko z możliwościa zdefiniowania zakładki. Żadne z
pól nie jest wymagane. Każdą zakładkę możemy wyedytować lub usunąć.
