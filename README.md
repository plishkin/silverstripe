## Silverstripe docker installer 

### Uses

- **[Silverstripe](https://silverstripe.com/)**
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

