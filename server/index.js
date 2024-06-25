const express = require('express');

const app = express();

app.get('/chunks', async (_request, response) => {
    const headers = {
        'Content-Type': 'text/event-stream',
        'Connection': 'keep-alive',
        'Cache-Control': 'no-cache'
    };
    response.writeHead(200, headers);

    response.write("Hello1");
    await new Promise((res) => setTimeout(res, 1000));
    response.write("Hello2");
    await new Promise((res) => setTimeout(res, 1000));
    response.write("Hello3");
    response.end();
});

app.listen(3000, () => {
    console.log(`You can now visit http://localhost:3000/chunks`);
});

