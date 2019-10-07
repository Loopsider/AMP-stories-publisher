## POSS System

### 1. Composer Install
```
composer install
```

### 2. Additional Environment (.env)
```
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_BASE_URL=
CLOUDINARY_SECURE_URL=
CLOUDINARY_API_BASE_URL=
CLOUDINARY_USER_NAME=

ADMIN_NAME=
ADMIN_EMAIL=
ADMIN_PASSWORD=
```
### 3. Database Setup

```
php artisan migrate
```

```
php artisan db:seed
```

### 4. Passport Setup
```
php artisan passport:client --personal
```

### 5. Redis Setup

#### Mac OS

- Install

```
brew install redis
```

- Start

```
redis-server
```

- Launch Redis on computer starts

```
ln -sfv /usr/local/opt/redis/*.plist ~/Library/LaunchAgents
```

- Start Redis server via `launchctl`

```
launchctl load ~/Library/LaunchAgents/homebrew.mxcl.redis.plist
```


#### Ubuntu

- Install

```
apt-get update
```

```
apt-get install redis-server
```

- Start

```
redis-server
```

### 6. NPM Setup

- install dependencies

```
npm install
```

- development
```
npm run watch-poll
```

- production
```
npm run production
```