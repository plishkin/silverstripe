## Simple sample application that shows 2 minutes countdown

![scrrenshot](https://raw.githubusercontent.com/plishkin/silverstripe/master/screenshot.png)

### Uses

- **[Silverstripe](https://silverstripe.com/)**
- **[Swoole](https://www.php.net/manual/en/intro.swoole.php)**
- **[Typescript](https://www.typescriptlang.org/)**
- **[React Native](https://reactnative.dev/)**
- **[SCSS](https://sass-lang.com/)**
- **[WebSocket](https://en.wikipedia.org/wiki/WebSocket)**
- **[Docker](https://www.docker.com/)**

## Installation

### Clone the project with git

```bash
git clone https://github.com/plishkin/silverstripe.git
```

Go to the cloned project folder

```bash
cd silverstripe
```

Copy and configure .env file

by default APP_PORT is 8082

```bash
cp .env.example .env
```


### Up and running with docker

#### Run build script
```bash
bash docker_build.sh
```

### Visit in your browser

http://localhost:8082

### Run tests

#### Backend

```bash
docker-compose exec fpm vendor/bin/phpunit app/tests
```

#### Frontend
Go to the cloned project folder

```bash
cd themes/app
```

```bash
yarn install
```
```bash
yarn run test
```
