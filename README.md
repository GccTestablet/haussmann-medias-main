# Haussmann Medias - Main
### Urls
> http://www.haussmann-medias.local/
### Run locally
#### Install
> Install Docker and Docker Compose
> https://docs.docker.com/install/
> https://docs.docker.com/compose/install/
> 
> Install Docker Sync
> https://docker-sync.readthedocs.io/en/latest/getting-started/installation.html
> 
#### First time only
```bash
./run init
```

#### Run every time
```bash
./run dev
```

#### Access in the  container
```bash
./run ssh
```

### Tools
#### Analyze and Cleanup
```bash
bin/phing analyze
bin/phing cleanup
```