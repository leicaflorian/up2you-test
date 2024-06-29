# Up2You Test

This is a simple test done for Up2You to evaluate my skills and code quality.

## Instructions

La prova consiste nello sviluppo di un piccolo back end per una libreria fittizia utilizzando il seguente stack
tecnologico: Laravel 10+, MySQL 8 (o SQLite 3).
La prova è suddivisa nelle seguenti parti:

1. Creazione e configurazione del progetto Laravel
2. Creazione e configurazione di un DB (migration, modelli...)
3. API CRUD
4. Autenticazione delle API
5. Test automatici back end [bonus]

## How to run

1. Clone the repository
2. Run `composer install`
3. Copy the `.env.example` file to `.env`
4. Run `php artisan key:generate`
5. Run `php artisan serve`

There is no need to run the migrations, as the database is an SQLite file located in `database/database.sqlite`,
and has already been seeded with the necessary data.
Nevertheless, you can view the migrations and seeders in the `database` folder and run them if you want to switch to a
different database.

## Tests
Tests are located in the `tests` folder, and they can be run with the command `php artisan test`
