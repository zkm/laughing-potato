# Web Programmer Test Project

Thanks for your interest in working at Sweetwater! We're always excited to meet awesome people. We've created this test to help us understand your programming chops.

When placing orders on a website, we provide a field for customers to add a quick comment to tell us something we should know about them or their order. We're supplying you a MySQL table with these various comments and want to see your approach the following tasks.


## Task 1 - Write a report that will display the comments from the table

Display the comments and group them into the following sections based on what the comment was about:
- Comments about candy
- Comments about call me / don't call me
- Comments about who referred me
- Comments about signature requirements upon delivery
- Miscellaneous comments (everything else)


## Task 2 - Populate the shipdate_expected field in this table with the date found in the `comments` field (where applicable)

The shipdate_expected field is currently populated with no date (0000-00-00). Some of comments included an "Expected Ship Date" in the text. Please parse out the date from the text and properly update the shipdate_expected field in the table


## How you'll build it

- You can use any VCS platform you like — such as Gitlab or Github — as long as your project is publicly accessible.
- Build your application so we can test it in-browser.
- Write your application using PHP
- We're interested in functionality, not design. It doesn't have to look pretty but your code should :-)
- Don't use any other JavaScript libraries, such as jQuery.
- Once you're done, send us the link to your project so we can look it over.


## Requirements

- __Commit often.__ We want to see your progress throughout the project.
- __Work quickly.__ This project was designed to be completed quickly, so don't spend too much time on it.
- __Write your own code.__ While we understand that there are pakages out there that take care of common problems, we ultimately want to see what _YOU_ can build, not what someone else has built.
- __Do your best work.__ We're using this project as a viewport into who you are as a developer. Show us what you can do!

## How to run

- With Docker (recommended):
	1) Build and start services
		 - docker-compose up -d --build
	2) Open the app
		 - http://localhost:8100
	3) Composer dependencies are installed during image build and also ensured on container start.

Notes
- Web server: php:8.1-apache (document root: public/)
- DB: MySQL 8 (exposed on host as 9906)
- Composer: installed in the image; vendor/ is persisted in a named volume

## How to run tests

- Easiest (interactive helper):
	- ./scripts/test.sh            # menu for All/Unit/Integration/Coverage
	- ./scripts/test.sh all        # run all tests
	- ./scripts/test.sh unit       # unit tests only
	- ./scripts/test.sh integration# integration tests only

- Direct commands (inside Docker):
	- docker-compose exec web vendor/bin/phpunit           # all tests
	- docker-compose exec web vendor/bin/phpunit tests/Unit
	- docker-compose exec web vendor/bin/phpunit tests/Integration

- Coverage (optional):
	- docker-compose exec web composer test-coverage
	- If you see "No code coverage driver available", we can add Xdebug/PCOV to the image.

Notes:
- Tests use Composer autoload and load environment from .env if present. Defaults match docker-compose (db/sweetwater_db).
- The legacy run-tests.sh has been removed in favor of standard Composer/PHPUnit.

## Database

- Hostnames and credentials (match docker-compose):
	- Host: db (from within containers) or localhost:9906 (from host)
	- User: sweetwater_user
	- Pass: sweetwater_pass
	- DB:   sweetwater_db

## Troubleshooting

- Error: require vendor/autoload.php failed
	- Cause: dependencies not installed yet
	- Fix:
		- docker-compose up -d --build (ensures install during build)
		- or run ./scripts/test.sh (installs on first run if missing)
