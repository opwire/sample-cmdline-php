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

## License

MIT

See [LICENSE](LICENSE) to see the full text.
