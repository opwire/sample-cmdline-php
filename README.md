# opwire-agent: sample command line in PHP

## Install

Clone example source code from github:

```shell
git clone https://github.com/opwire/sample-cmdline-php.git
```

Change the project home to current working directory:

```shell
cd sample-cmdline-php
```

Download and extract the latest [`opwire-agent`](https://github.com/opwire/opwire-agent/releases/latest) program into this directory:

![project-home-dir](https://raw.github.com/opwire/sample-cmdline-php/master/docs/assets/images/ls.png)

## Test the service using a web browser

Execute the following command:

```shell
./opwire-agent -p=8888 --default-command="php example.php"
```

Open the URL `http://localhost:8888/run?type=microservice&type=php`:

![example-output](https://raw.github.com/opwire/sample-cmdline-php/master/docs/assets/images/example.png)

## Test the service using `curl`

### Default input/output format (`json`)

Execute the following command:

```shell
./opwire-agent -p=8888 --default-command="php example.php"
```

> Notice: above command is similar to
> 
> ```shell
> ./opwire-agent -p=8888 --default-command="php example.php --format=json"
> ```
> 
> or
> 
> ```shell
> ./opwire-agent -p=8888 --default-command="php example.php --input-format=json --output-format=json"
> ```

#### Valid input (a JSON object)

Make a HTTP request with `curl`:

```curl
curl -v \
  --request POST \
  --url 'http://localhost:8888/run?type=microservice&type=php' \
  --data '{
  "name": "Opwire",
  "url": "https://opwire.org/"
}'
```

Result:

```plain
> POST /run?type=microservice&type=php HTTP/1.1
> User-Agent: curl/7.35.0
> Host: localhost:8888
> Accept: */*
> Content-Length: 52
> Content-Type: application/x-www-form-urlencoded
>
* upload completely sent off: 52 out of 52 bytes
< HTTP/1.1 200 OK
< Content-Type: text/plain
< X-Exec-Duration: 0.038846
< Date: Fri, 15 Mar 2019 11:16:53 GMT
< Content-Length: 686
<
{
    "OPWIRE_EDITION": {
        "revision": "b4cec6a",
        "version": "v1.0.1"
    },
    "OPWIRE_REQUEST": {
        "header": {
            "Accept": [
                "*\/*"
            ],
            "Content-Length": [
                "52"
            ],
            "Content-Type": [
                "application\/x-www-form-urlencoded"
            ],
            "User-Agent": [
                "curl\/7.35.0"
            ]
        },
        "query": {
            "type": [
                "microservice",
                "php"
            ]
        },
        "params": null
    },
    "input": {
        "name": "Opwire",
        "url": "https:\/\/opwire.org\/"
    }
}
```

#### Invalid input (not a JSON object)

Make a HTTP request with `curl`:

```curl
curl -v \
  --request POST \
  --url 'http://localhost:8888/run?type=microservice&type=php' \
  --data 'Not a JSON object'
```

Result:

```plain
> POST /run?type=microservice&type=php HTTP/1.1
> User-Agent: curl/7.35.0
> Host: localhost:8888
> Accept: */*
> Content-Length: 17
> Content-Type: application/x-www-form-urlencoded
>
* upload completely sent off: 17 out of 17 bytes
< HTTP/1.1 500 Internal Server Error
< Content-Type: text/plain
< X-Error-Message: exit status 1
< Date: Fri, 15 Mar 2019 11:17:44 GMT
< Content-Length: 76
<
{
    "name": "SyntaxError",
    "message": "Syntax error, malformed JSON"
}
```

### JSON input, plaintext output

Execute the following command:

```shell
./opwire-agent -p=8888 \
  --default-command="php example.php --input-format=json --output-format=text"
```

#### Valid input (a JSON object)

Make a HTTP request with `curl`:

```curl
curl -v \
  --request POST \
  --url 'http://localhost:8888/run?type=microservice&type=php' \
  --data '{
  "name": "Opwire",
  "url": "https://opwire.org/"
}'
```

Result:

```plain
> POST /run?type=microservice&type=php HTTP/1.1
> User-Agent: curl/7.35.0
> Host: localhost:8888
> Accept: */*
> Content-Length: 54
> Content-Type: application/x-www-form-urlencoded
>
* upload completely sent off: 54 out of 54 bytes
< HTTP/1.1 200 OK
< Content-Type: text/plain
< X-Exec-Duration: 0.018843
< Date: Fri, 15 Mar 2019 11:19:36 GMT
< Content-Length: 554
<
OPWIRE_EDITION:
{
    "revision": "b4cec6a",
    "version": "v1.0.1"
}

OPWIRE_REQUEST:
{
    "header": {
        "Accept": [
            "*\/*"
        ],
        "Content-Length": [
            "54"
        ],
        "Content-Type": [
            "application\/x-www-form-urlencoded"
        ],
        "User-Agent": [
            "curl\/7.35.0"
        ]
    },
    "query": {
        "type": [
            "microservice",
            "php"
        ]
    },
    "params": null
}

input:
{
    "name": "Opwire",
    "url": "https:\/\/opwire.org\/"
}
```

#### Invalid input (not a JSON object)

Make a HTTP request with `curl`:

```curl
curl -v \
  --request POST \
  --url 'http://localhost:8888/run?type=microservice&type=php' \
  --data 'Not a JSON object'
```

Result:

```plain
> POST /run?type=microservice&type=php HTTP/1.1
> User-Agent: curl/7.35.0
> Host: localhost:8888
> Accept: */*
> Content-Length: 17
> Content-Type: application/x-www-form-urlencoded
>
* upload completely sent off: 17 out of 17 bytes
< HTTP/1.1 500 Internal Server Error
< Content-Type: text/plain
< X-Error-Message: exit status 1
< Date: Fri, 15 Mar 2019 11:20:22 GMT
< Content-Length: 28
<
Syntax error, malformed JSON
```

## License

MIT

See [LICENSE](LICENSE) to see the full text.
