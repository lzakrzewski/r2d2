install-composer:
	docker run -it --rm \
	-v "${PWD}":/r2d2 \
	-w /r2d2 \
	prooph/composer:7.2 install

r2d2:
	docker run -it --rm \
	-v "${PWD}":/r2d2 \
	-w /r2d2 \
	--entrypoint=php \
	prooph/composer:7.2 bin/console r2d2

tests-ci:
	docker run -it --rm \
	-v "${PWD}":/r2d2 \
	-w /r2d2 \
	prooph/composer:7.2 tests-all

build: install-composer

build-and-run: build r2d2