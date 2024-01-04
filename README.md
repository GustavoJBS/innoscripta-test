# News Aggregator

## 🍬 Stack includes

* API (Port: 8000)
  * Laravel (latest version)
  * PHP 8.2 - FPM
  * Mysql (and separate database for testing)
  * Redis
* Client (Port: 3000)
  * Next 14
  * Typescript

## ⚙ Installation

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