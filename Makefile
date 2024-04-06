install:
		composer install

validate:
		composer validate

lint:
		composer exec --verbose phpcs -- --standard=PSR12 src bin tests

lint-fix:
		composer exec phpcbf -- --standard=PSR12 -v src bin tests

#for Hexlet functional programming requirements!
phpstan:
		composer exec -v phpstan analyse src bin

help:
		./bin/gendiff -h

demo1:
		./bin/gendiff tests/fixtures/file1-flat.json tests/fixtures/file2-flat.json

demo2:
		./bin/gendiff tests/fixtures/file1-nested.json tests/fixtures/file2-nested.json

demo3:
		./bin/gendiff --format plain tests/fixtures/file1-flat.yml tests/fixtures/file2-flat.yml

demo4:
		./bin/gendiff --format plain tests/fixtures/file1-nested.yml tests/fixtures/file2-nested.yml

test:
		composer exec --verbose phpunit tests

test-dox:
		composer exec -v phpunit -- --colors=always --testdox

test-coverage-text:
		XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

test-coverage-text-report:
		XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover ./test-reports/coverage.xml

test-coverage-html-report:
		XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-html ./test-reports/html

publish-coverage-to-cc:
		bash ./scripts/cc-coverage.sh