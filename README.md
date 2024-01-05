# News Aggregator

## 🍬 Stack

* API (http://localhost:8000)
  * PHP 8.2
  * PestPHP
  * Laravel 
  * Mysql
  * Redis
* Client (http://localhost:8000)
  * Next 14
  * Typescript
  * TailwindCSS

## 🧑‍💻 Installation

```bash
git clone https://github.com/GustavoJBS/news-hub.git

cd news-hub
```

Copy Env Examples at root, backend, and frontend folders

```bash
cp .env.example .env

cd backend

cp .env.example .env

cd ../frontend

cp .env.example .env
```


Build Docker Container

```bash
docker-compose up -d
```

Get Backend Container Name (news-hub-backend-1)

```bash
docker ps
```

Generate New Laravel Application Key

```bash
docker exec -it news-hub-backend-1 php artisan key:generate
```

Run Backend Migrations

```bash
docker exec -it news-hub-backend-1 php artisan migrate --seed
```

Insert News API Credentials at Backend .env

```bash
cd backend
nano .env
```

* [News Org API](https://newsapi.org/)
  * NEWS_API_KEY=
* [The Guardian API](https://open-platform.theguardian.com/)
  * GUARDIAN_API_KEY=
* [New York Times API](https://developer.nytimes.com/)
  * NY_TIMES_API_KEY=

Clear Application CACHE

```bash
docker exec -it news-hub-backend-1 php artisan optimize:clear
```

Command to update Database Articles (Artisan Command)

```bash
docker exec -it news-hub-backend-1 php artisan horizon
docker exec -it news-hub-backend-1 php artisan app:sync-source-articles-from-apis
```

Command to update Database Articles (Scheduler Command)

```bash
docker exec -it news-hub-backend-1 php artisan schedule:test
```

Backend Tests

```bash
docker exec -it news-hub-backend-1 php artisan test
```
