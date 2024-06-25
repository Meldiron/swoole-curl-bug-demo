# swoole-curl-bug-demo
Demo of chunked response CURL hook bug in Swoole

## 🛠️ Requirements

- Docker installed and running
- Prepare network: `docker network create my-app`

## 🐛 Reproduce bug

### 1. Prepare server

- Enter server folder: `cd server`
- Start server: `docker run --rm -it --name my-app-server --network my-app -p 3000:3000 -w /root -v ./:/root node sh -c "npm install && node index.js"`

> Server is implemented using Node.js and Express, but tht is not relevant. We just need server with chunked response.

You can visit port `localhost:3000/chunks` and see 3 hello world messages being printed, with 1 second gap between each. After 3 messages, resposne finishes.

Keep this running in the background.

### 2. Run Swoole CURL client

- Enter folder with Swoole app: `cd client`
- Run client: `docker run --rm -it -v ./:/root -w /root -p 5000:5000 --network my-app phpswoole/swoole:4.8.12-php8.0-alpine sh -c "composer install --profile --ignore-platform-reqs && php index.php"`

Now visit `localhost:5000/chunks`, this will trigger CURL request from Swoole server to Node server above. Var_dump should be done each chunk, so 3 times in total (in docker logs)

Seemingly, it works as expected. I could not reproduce the bug yet. I need to compare environments more deeply.

```
string(13) "Chunk: Hello1"
string(13) "Chunk: Hello2"
string(13) "Chunk: Hello3"
string(9) "Response:"
bool(true)
string(5) "Code:"
int(200)
```